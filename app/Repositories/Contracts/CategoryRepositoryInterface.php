<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function find(int $id): ?Category;

    public function create(array $data): Category;

    public function update(Category $category, array $data): bool;

    public function delete(Category $category): bool;

    public function getAll(): Collection;

    public function getAllOrdered(): Collection;

    public function getAllWithChallenges(): Collection;
}
