<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHintRequest;
use App\Repositories\Contracts\ChallengeRepositoryInterface;
use App\Repositories\Contracts\HintRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class HintController extends Controller
{
    public function __construct(
        private HintRepositoryInterface $hintRepo,
        private ChallengeRepositoryInterface $challengeRepo,
    ) {}

    public function store(StoreHintRequest $request): RedirectResponse
    {
        $hint = $this->hintRepo->create($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['hint_id' => $hint->id, 'challenge_id' => $hint->challenge_id])
            ->log('Created hint');

        return back()->with('success', 'Hint added successfully.');
    }
}
