<?php

namespace App\Services;

use App\DTOs\HintUnlockResult;
use App\DTOs\SubmissionResult;
use App\Models\Challenge;
use App\Models\Hint;
use App\Models\User;
use App\Repositories\Contracts\ChallengeRepositoryInterface;
use App\Repositories\Contracts\HintRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ChallengeService
{
    public function __construct(
        private ChallengeRepositoryInterface $challengeRepo,
        private UserRepositoryInterface $userRepo,
        private HintRepositoryInterface $hintRepo,
    ) {}

    public function submitFlag(User $user, Challenge $challenge, string $flag): SubmissionResult
    {
        if ($this->challengeRepo->isSolvedByUserOrTeam($challenge, $user)) {
            return SubmissionResult::alreadySolved();
        }

        if ($flag !== $challenge->flag) {
            activity()
                ->causedBy($user)
                ->withProperties(['challenge_id' => $challenge->id, 'submitted_flag' => $flag])
                ->log('Failed flag submission');

            return SubmissionResult::incorrect();
        }

        return DB::transaction(function () use ($user, $challenge) {
            $this->challengeRepo->markAsSolved($challenge, $user);
            $this->userRepo->incrementScore($user, $challenge->points);

            activity()
                ->causedBy($user)
                ->withProperties(['challenge_id' => $challenge->id, 'points' => $challenge->points])
                ->log('Challenge solved');

            return SubmissionResult::correct($challenge->points);
        });
    }

    public function unlockHint(User $user, Hint $hint): HintUnlockResult
    {
        if ($this->hintRepo->isUnlockedByUser($hint, $user)) {
            return HintUnlockResult::alreadyUnlocked($hint->content);
        }

        if ($user->score < $hint->cost) {
            return HintUnlockResult::insufficientPoints($hint->cost, $user->score);
        }

        return DB::transaction(function () use ($user, $hint) {
            $this->hintRepo->unlockForUser($hint, $user);
            $this->userRepo->decrementScore($user, $hint->cost);

            activity()
                ->causedBy($user)
                ->withProperties(['hint_id' => $hint->id, 'cost' => $hint->cost])
                ->log('Hint unlocked');

            return HintUnlockResult::unlocked($hint->content, $hint->cost);
        });
    }

    public function isSolvedByUserOrTeam(User $user, Challenge $challenge): bool
    {
        return $this->challengeRepo->isSolvedByUserOrTeam($challenge, $user);
    }

    public function getChallengeWithDetails(int $challengeId): ?Challenge
    {
        return $this->challengeRepo->findWithRelations($challengeId, ['hints', 'category']);
    }

    public function getAllGroupedByCategory()
    {
        return $this->challengeRepo->getAllGroupedByCategory();
    }
}
