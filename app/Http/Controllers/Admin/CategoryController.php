<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepo,
    ) {}

    public function index(): View
    {
        $categories = $this->categoryRepo->getAllWithChallenges();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $category = $this->categoryRepo->create($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['category_id' => $category->id, 'name' => $category->name])
            ->log('Created category');

        return back()->with('success', 'Category created successfully.');
    }
}
