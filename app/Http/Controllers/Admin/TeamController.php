<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamRequest;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct(
        private TeamService $teamService,
    ) {}

    public function index(): View
    {
        $teams = $this->teamService->getAllTeamsWithMembers();

        return view('admin.teams.index', compact('teams'));
    }

    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $this->teamService->createTeam($request->validated('name'));

        return back()->with('success', 'Team created successfully.');
    }
}
