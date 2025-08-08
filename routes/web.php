<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    
    Route::get('dashboard/users', function () {
        $users = \App\Models\User::with(['role', 'level'])
            ->withCount('achievements', 'streaks')
            ->paginate(20);
            
        return Inertia::render('dashboard/users', [
            'users' => $users
        ]);
    })->name('dashboard.users');
    
    // Achievements CRUD
    Route::resource('dashboard/achievements', App\Http\Controllers\Dashboard\AchievementController::class)
        ->names([
            'index' => 'dashboard.achievements.index',
            'create' => 'dashboard.achievements.create',
            'store' => 'dashboard.achievements.store',
            'show' => 'dashboard.achievements.show',
            'edit' => 'dashboard.achievements.edit',
            'update' => 'dashboard.achievements.update',
            'destroy' => 'dashboard.achievements.destroy',
        ]);
    
    // Levels CRUD
    Route::resource('dashboard/levels', App\Http\Controllers\Dashboard\LevelController::class)
        ->names([
            'index' => 'dashboard.levels.index',
            'create' => 'dashboard.levels.create',
            'store' => 'dashboard.levels.store',
            'show' => 'dashboard.levels.show',
            'edit' => 'dashboard.levels.edit',
            'update' => 'dashboard.levels.update',
            'destroy' => 'dashboard.levels.destroy',
        ]);
    
    // Languages CRUD
    Route::resource('dashboard/languages', App\Http\Controllers\Dashboard\LanguageController::class)
        ->names([
            'index' => 'dashboard.languages.index',
            'create' => 'dashboard.languages.create',
            'store' => 'dashboard.languages.store',
            'show' => 'dashboard.languages.show',
            'edit' => 'dashboard.languages.edit',
            'update' => 'dashboard.languages.update',
            'destroy' => 'dashboard.languages.destroy',
        ]);
    
    // Language toggle route
    Route::patch('dashboard/languages/{language}/toggle', [App\Http\Controllers\Dashboard\LanguageController::class, 'toggle'])
        ->name('dashboard.languages.toggle');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
