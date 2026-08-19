<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::resource('tasks', TaskController::class);

    Route::resource('teams', TeamController::class)
        ->only(['index', 'create', 'store']);

    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'store']);

    Route::get('team/change/{teamId}', [TeamController::class, 'changeCurrentTeam'])
        ->name('team.change');
});

require __DIR__ . '/settings.php';
