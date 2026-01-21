<?php

namespace App\Http\Controllers;

use App\Services\ChallengeService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private ChallengeService $challengeService,
    ) {}

    public function index(): View
    {
        $categories = $this->challengeService->getAllGroupedByCategory();

        return view('home', compact('categories'));
    }
}
