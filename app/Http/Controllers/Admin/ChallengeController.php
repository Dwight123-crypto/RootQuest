<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreChallengeRequest;
use App\Models\Challenge;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ChallengeRepositoryInterface;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChallengeController extends Controller
{
    public function __construct(
        private ChallengeRepositoryInterface $challengeRepo,
        private CategoryRepositoryInterface $categoryRepo,
        private FileUploadService $fileUploadService,
    ) {}

    public function index(): View
    {
        $challenges = $this->challengeRepo->getAllWithHints();
        $categories = $this->categoryRepo->getAllOrdered();

        return view('admin.challenges.index', compact('challenges', 'categories'));
    }

    public function store(StoreChallengeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('challenge_file')) {
            $data['file_path'] = $this->fileUploadService->uploadChallengeFile(
                $request->file('challenge_file')
            );
        }

        unset($data['challenge_file']);

        $challenge = $this->challengeRepo->create($data);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['challenge_id' => $challenge->id, 'title' => $challenge->title])
            ->log('Created challenge');

        return back()->with('success', 'Challenge created successfully.');
    }

    public function destroy(Challenge $challenge): RedirectResponse
    {
        if ($challenge->file_path) {
            $this->fileUploadService->deleteFile($challenge->file_path);
        }

        $this->challengeRepo->delete($challenge);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['challenge_id' => $challenge->id])
            ->log('Deleted challenge');

        return back()->with('success', 'Challenge deleted successfully.');
    }
}
