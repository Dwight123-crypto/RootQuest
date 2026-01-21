<?php

namespace App\Repositories\Eloquent;

use App\Models\Hint;
use App\Models\User;
use App\Repositories\Contracts\HintRepositoryInterface;
use Illuminate\Support\Collection;

class HintRepository implements HintRepositoryInterface
{
    public function find(int $id): ?Hint
    {
        return Hint::find($id);
    }

    public function create(array $data): Hint
    {
        return Hint::create($data);
    }

    public function update(Hint $hint, array $data): bool
    {
        return $hint->update($data);
    }

    public function delete(Hint $hint): bool
    {
        return $hint->delete();
    }

    public function getByChallenge(int $challengeId): Collection
    {
        return Hint::where('challenge_id', $challengeId)->get();
    }

    public function unlockForUser(Hint $hint, User $user): void
    {
        $user->unlockedHints()->attach($hint->id);
    }

    public function isUnlockedByUser(Hint $hint, User $user): bool
    {
        return $hint->isUnlockedBy($user);
    }
}
