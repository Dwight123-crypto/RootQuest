<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index(): View
    {
        $logs = Activity::with('causer')
            ->latest()
            ->paginate(50);

        return view('admin.logs.index', compact('logs'));
    }
}
