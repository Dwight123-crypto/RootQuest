<?php

namespace App\Http\Controllers;

use App\Services\ScoreboardService;
use Illuminate\View\View;

class ScoreboardController extends Controller
{
    public function __construct(
        private ScoreboardService $scoreboardService,
    ) {}

    public function users(): View
    {
        $user = auth()->user();
        $data = $this->scoreboardService->getUserScoreboardData($user);

        return view('scoreboard.users', $data);
    }

    public function teams(): View
    {
        $user = auth()->user();
        $data = $this->scoreboardService->getTeamScoreboardData($user->team);

        return view('scoreboard.teams', $data);
    }
}
