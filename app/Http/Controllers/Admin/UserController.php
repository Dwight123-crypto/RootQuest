<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
    ) {}

    public function index(): View
    {
        $users = $this->userRepo->getAllNonAdminUsers();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(UpdateUserRoleRequest $request): RedirectResponse
    {
        $user = $this->userRepo->find($request->validated('user_id'));

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $this->userRepo->update($user, ['is_admin' => $request->validated('is_admin')]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'target_user_id' => $user->id,
                'new_role' => $request->validated('is_admin') ? 'admin' : 'user',
            ])
            ->log('Updated user role');

        return back()->with('success', 'User role updated successfully.');
    }
}
