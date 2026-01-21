<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function getRankedUsers(int $limit = 50): Collection
    {
        return User::ranked()->take($limit)->get();
    }

    public function getUserRank(User $user): int
    {
        if ($user->is_admin) {
            return 0;
        }

        return User::nonAdmin()
            ->where('score', '>', $user->score)
            ->count() + 1;
    }

    public function incrementScore(User $user, int $points): bool
    {
        return $user->increment('score', $points);
    }

    public function decrementScore(User $user, int $points): bool
    {
        return $user->decrement('score', $points);
    }

    public function getAllNonAdminUsers(): Collection
    {
        return User::nonAdmin()->orderBy('name')->get();
    }

    public function isFirstUser(): bool
    {
        return User::count() === 0;
    }
}
