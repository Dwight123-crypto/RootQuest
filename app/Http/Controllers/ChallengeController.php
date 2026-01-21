<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitFlagRequest;
use App\Http\Requests\UnlockHintRequest;
use App\Models\Challenge;
use App\Models\Hint;
use App\Services\ChallengeService;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChallengeController extends Controller
{
    public function __construct(
        private ChallengeService $challengeService,
        private FileUploadService $fileUploadService,
    ) {}

    public function show(Challenge $challenge): View
    {
        $challenge->load(['hints', 'category']);
        $user = auth()->user();

        $isSolved = $this->challengeService->isSolvedByUserOrTeam($user, $challenge);
        $unlockedHintIds = $user->unlockedHints()->pluck('hints.id')->toArray();

        return view('challenges.show', compact('challenge', 'isSolved', 'unlockedHintIds'));
    }

    public function submitFlag(SubmitFlagRequest $request, Challenge $challenge): RedirectResponse
    {
        $result = $this->challengeService->submitFlag(
            auth()->user(),
            $challenge,
            $request->validated('flag'),
        );

        return back()->with(
            $result->success ? 'success' : 'error',
            $result->message,
        );
    }

    public function unlockHint(UnlockHintRequest $request, Challenge $challenge): RedirectResponse
    {
        $hint = Hint::findOrFail($request->validated('hint_id'));

        if ($hint->challenge_id !== $challenge->id) {
            abort(404);
        }

        $result = $this->challengeService->unlockHint(auth()->user(), $hint);

        return back()->with(
            $result->success ? 'success' : 'error',
            $result->message,
        );
    }

    public function downloadFile(Challenge $challenge): StreamedResponse
    {
        if (!$challenge->hasFile()) {
            abort(404);
        }

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['challenge_id' => $challenge->id])
            ->log('Downloaded challenge file');

        return $this->fileUploadService->downloadChallengeFile($challenge);
    }
}
