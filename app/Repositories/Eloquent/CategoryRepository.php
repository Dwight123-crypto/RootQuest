<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    public function getAll(): Collection
    {
        return Category::all();
    }

    public function getAllOrdered(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function getAllWithChallenges(): Collection
    {
        return Category::with(['challenges' => function ($query) {
            $query->orderBy('points');
        }])->orderBy('name')->get();
    }
}
