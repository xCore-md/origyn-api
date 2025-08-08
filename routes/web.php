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
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
