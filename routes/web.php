<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScoreboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Challenges
    Route::get('/challenges/{challenge}', [ChallengeController::class, 'show'])->name('challenges.show');
    Route::post('/challenges/{challenge}/submit', [ChallengeController::class, 'submitFlag'])
        ->name('challenges.submit')
        ->middleware('throttle:10,1');
    Route::post('/challenges/{challenge}/unlock-hint', [ChallengeController::class, 'unlockHint'])
        ->name('challenges.unlock-hint');
    Route::get('/challenges/{challenge}/download', [ChallengeController::class, 'downloadFile'])
        ->name('challenges.download');

    // Scoreboards
    Route::get('/scoreboard/users', [ScoreboardController::class, 'users'])->name('scoreboard.users');
    Route::get('/scoreboard/teams', [ScoreboardController::class, 'teams'])->name('scoreboard.teams');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');

    // Challenges
    Route::get('/challenges', [Admin\ChallengeController::class, 'index'])->name('challenges.index');
    Route::post('/challenges', [Admin\ChallengeController::class, 'store'])->name('challenges.store');
    Route::delete('/challenges/{challenge}', [Admin\ChallengeController::class, 'destroy'])->name('challenges.destroy');

    // Hints
    Route::post('/hints', [Admin\HintController::class, 'store'])->name('hints.store');

    // Teams
    Route::get('/teams', [Admin\TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams', [Admin\TeamController::class, 'store'])->name('teams.store');

    // Users
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users/role', [Admin\UserController::class, 'updateRole'])->name('users.update-role');

    // Logs
    Route::get('/logs', [Admin\LogController::class, 'index'])->name('logs.index');
});

require __DIR__.'/auth.php';
