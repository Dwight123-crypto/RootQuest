<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Repositories\Contracts\TeamRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class TeamService
{
    public function __construct(
        private TeamRepositoryInterface $teamRepo,
        private UserRepositoryInterface $userRepo,
    ) {}

    public function createTeam(string $name): Team
    {
        $team = $this->teamRepo->create(['name' => $name]);

        activity()
            ->withProperties(['team_id' => $team->id, 'name' => $name])
            ->log('Team created');

        return $team;
    }

    public function assignUserToTeam(User $user, Team $team): bool
    {
        if ($team->is_full) {
            return false;
        }

        $this->userRepo->update($user, ['team_id' => $team->id]);

        activity()
            ->causedBy($user)
            ->withProperties(['team_id' => $team->id])
            ->log('Joined team');

        return true;
    }

    public function removeUserFromTeam(User $user): bool
    {
        if (!$user->team_id) {
            return false;
        }

        $oldTeamId = $user->team_id;
        $this->userRepo->update($user, ['team_id' => null]);

        activity()
            ->causedBy($user)
            ->withProperties(['old_team_id' => $oldTeamId])
            ->log('Left team');

        return true;
    }

    public function getAllTeamsWithMembers()
    {
        return $this->teamRepo->getAllWithMembers();
    }

    public function getAvailableTeams()
    {
        return $this->teamRepo->getAvailableTeams();
    }

    public function canJoinTeam(Team $team): bool
    {
        return !$team->is_full;
    }
}
