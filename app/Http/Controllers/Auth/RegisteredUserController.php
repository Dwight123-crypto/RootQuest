<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Repositories\Contracts\TeamRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private TeamRepositoryInterface $teamRepo,
    ) {}

    public function create(): View
    {
        $teams = $this->teamRepo->getAvailableTeams();

        return view('auth.register', compact('teams'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'team_id' => ['nullable', 'exists:teams,id'],
        ];

        $request->validate($rules);

        if ($request->team_id) {
            $team = Team::find($request->team_id);
            if ($team && $team->is_full) {
                return back()->withErrors(['team_id' => 'This team has reached the maximum number of members (4).']);
            }
        }

        $isFirstUser = $this->userRepo->isFirstUser();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'team_id' => $request->team_id,
            'is_admin' => $isFirstUser,
            'score' => 0,
        ]);

        activity()
            ->causedBy($user)
            ->withProperties([
                'is_first_user' => $isFirstUser,
                'team_id' => $request->team_id,
            ])
            ->log('User registered');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
