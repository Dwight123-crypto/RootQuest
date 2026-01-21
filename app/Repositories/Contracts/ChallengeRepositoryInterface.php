<?php

namespace App\Repositories\Contracts;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Collection;

interface ChallengeRepositoryInterface
{
    public function find(int $id): ?Challenge;

    public function findWithRelations(int $id, array $relations = []): ?Challenge;

    public function create(array $data): Challenge;

    public function update(Challenge $challenge, array $data): bool;

    public function delete(Challenge $challenge): bool;

    public function getAllGroupedByCategory(): Collection;

    public function getByCategoryOrdered(int $categoryId): Collection;

    public function markAsSolved(Challenge $challenge, User $user): void;

    public function isSolvedByUserOrTeam(Challenge $challenge, User $user): bool;

    public function getAllWithHints(): Collection;
}
