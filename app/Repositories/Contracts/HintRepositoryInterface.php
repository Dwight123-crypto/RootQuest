<?php

namespace App\Repositories\Contracts;

use App\Models\Hint;
use App\Models\User;
use Illuminate\Support\Collection;

interface HintRepositoryInterface
{
    public function find(int $id): ?Hint;

    public function create(array $data): Hint;

    public function update(Hint $hint, array $data): bool;

    public function delete(Hint $hint): bool;

    public function getByChallenge(int $challengeId): Collection;

    public function unlockForUser(Hint $hint, User $user): void;

    public function isUnlockedByUser(Hint $hint, User $user): bool;
}
