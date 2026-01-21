<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Repositories\Contracts\TeamRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TeamRepository implements TeamRepositoryInterface
{
    public function find(int $id): ?Team
    {
        return Team::find($id);
    }

    public function create(array $data): Team
    {
        return Team::create($data);
    }

    public function update(Team $team, array $data): bool
    {
        return $team->update($data);
    }

    public function delete(Team $team): bool
    {
        return $team->delete();
    }

    public function getAll(): Collection
    {
        return Team::all();
    }

    public function getAllWithMembers(): Collection
    {
        return Team::withCount('members')
            ->with('members:id,name,team_id')
            ->orderBy('name')
            ->get();
    }

    public function getRankedTeams(int $limit = 50): Collection
    {
        return Team::select('teams.*')
            ->leftJoin('users', function ($join) {
                $join->on('teams.id', '=', 'users.team_id')
                    ->where('users.is_admin', false);
            })
            ->groupBy('teams.id')
            ->selectRaw('COALESCE(SUM(users.score), 0) as total_score')
            ->orderByDesc('total_score')
            ->take($limit)
            ->get();
    }

    public function getTeamRank(Team $team): int
    {
        $teamScore = $team->total_score;

        return DB::table('teams')
            ->leftJoin('users', function ($join) {
                $join->on('teams.id', '=', 'users.team_id')
                    ->where('users.is_admin', false);
            })
            ->groupBy('teams.id')
            ->havingRaw('COALESCE(SUM(users.score), 0) > ?', [$teamScore])
            ->count() + 1;
    }

    public function getAvailableTeams(): Collection
    {
        return Team::withCount('members')
            ->get()
            ->filter(fn ($team) => $team->members_count < Team::MAX_MEMBERS)
            ->sortBy('name')
            ->values();
    }
}
