<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function find(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): bool;

    public function getRankedUsers(int $limit = 50): Collection;

    public function getUserRank(User $user): int;

    public function incrementScore(User $user, int $points): bool;

    public function decrementScore(User $user, int $points): bool;

    public function getAllNonAdminUsers(): Collection;

    public function isFirstUser(): bool;
}
