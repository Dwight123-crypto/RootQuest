<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Repositories\Contracts\TeamRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class ScoreboardService
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private TeamRepositoryInterface $teamRepo,
    ) {}

    public function getUserRanking(int $limit = 50): Collection
    {
        return $this->userRepo->getRankedUsers($limit);
    }

    public function getTeamRanking(int $limit = 50): Collection
    {
        return $this->teamRepo->getRankedTeams($limit);
    }

    public function getUserRank(User $user): int
    {
        return $this->userRepo->getUserRank($user);
    }

    public function getTeamRank(Team $team): int
    {
        return $this->teamRepo->getTeamRank($team);
    }

    public function getUserScoreboardData(User $user, int $limit = 50): array
    {
        return [
            'rankings' => $this->getUserRanking($limit),
            'currentUserRank' => $user->is_admin ? null : $this->getUserRank($user),
            'currentUserScore' => $user->score,
        ];
    }

    public function getTeamScoreboardData(?Team $team, int $limit = 50): array
    {
        return [
            'rankings' => $this->getTeamRanking($limit),
            'currentTeamRank' => $team ? $this->getTeamRank($team) : null,
            'currentTeamScore' => $team ? $team->total_score : null,
        ];
    }
}
