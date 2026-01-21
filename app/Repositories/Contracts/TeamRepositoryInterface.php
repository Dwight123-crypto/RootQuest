<?php

namespace App\Repositories\Contracts;

use App\Models\Team;
use Illuminate\Support\Collection;

interface TeamRepositoryInterface
{
    public function find(int $id): ?Team;

    public function create(array $data): Team;

    public function update(Team $team, array $data): bool;

    public function delete(Team $team): bool;

    public function getAll(): Collection;

    public function getAllWithMembers(): Collection;

    public function getRankedTeams(int $limit = 50): Collection;

    public function getTeamRank(Team $team): int;

    public function getAvailableTeams(): Collection;
}
