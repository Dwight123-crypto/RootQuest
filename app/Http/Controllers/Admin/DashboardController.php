<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Challenge;
use App\Models\Team;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::nonAdmin()->count(),
            'teams' => Team::count(),
            'categories' => Category::count(),
            'challenges' => Challenge::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
