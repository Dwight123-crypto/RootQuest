<?php

namespace App\Repositories\Eloquent;

use App\Models\Challenge;
use App\Models\Category;
use App\Models\User;
use App\Repositories\Contracts\ChallengeRepositoryInterface;
use Illuminate\Support\Collection;

class ChallengeRepository implements ChallengeRepositoryInterface
{
    public function find(int $id): ?Challenge
    {
        return Challenge::find($id);
    }

    public function findWithRelations(int $id, array $relations = []): ?Challenge
    {
        return Challenge::with($relations)->find($id);
    }

    public function create(array $data): Challenge
    {
        return Challenge::create($data);
    }

    public function update(Challenge $challenge, array $data): bool
    {
        return $challenge->update($data);
    }

    public function delete(Challenge $challenge): bool
    {
        return $challenge->delete();
    }

    public function getAllGroupedByCategory(): Collection
    {
        return Category::with(['challenges' => function ($query) {
            $query->orderBy('points');
        }])->orderBy('name')->get();
    }

    public function getByCategoryOrdered(int $categoryId): Collection
    {
        return Challenge::byCategory($categoryId)
            ->orderedByPoints()
            ->get();
    }

    public function markAsSolved(Challenge $challenge, User $user): void
    {
        $challenge->solvers()->attach($user->id, [
            'solved_at' => now(),
        ]);
    }

    public function isSolvedByUserOrTeam(Challenge $challenge, User $user): bool
    {
        return $challenge->isSolvedByTeam($user);
    }

    public function getAllWithHints(): Collection
    {
        return Challenge::with(['hints', 'category'])->get();
    }
}
