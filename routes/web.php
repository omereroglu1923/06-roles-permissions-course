<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\User;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::resource('tasks', TaskController::class);

    Route::resource('teams', TeamController::class)
        ->only(['index', 'create', 'store']);

    Route::get('team/change/{teamId}', [TeamController::class, 'changeCurrentTeam'])
        ->name('team.change');

    Route::prefix('admin')
        ->name('admin.')
        ->middleware(IsAdminMiddleware::class)
        ->group(function () {
            Route::resource('tasks', Admin\TaskController::class);
        });

    Route::prefix('user')
        ->name('user.')
        ->group(function () {
            Route::resource('tasks', User\TaskController::class);
        });
});

require __DIR__.'/settings.php';
