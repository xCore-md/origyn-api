<?php

use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\StreakController;
use Illuminate\Support\Facades\Route;

// Guest routes (no authentication required)
Route::post('/guest/create', [GuestController::class, 'createGuest']);
Route::post('/guest/auth', [GuestController::class, 'authenticateGuest']);

// Language routes
Route::get('/languages', function () {
    return response()->json([
        'success' => true,
        'languages' => \App\Models\Language::active()
            ->select('id', 'language', 'code', 'emoji')
            ->orderBy('language')
            ->get(),
    ]);
});

// Disciplines routes
Route::get('/disciplines', function () {
    return response()->json([
        'success' => true,
        'disciplines' => \App\Models\Discipline::where('is_active', true)
            ->select('id', 'name', 'icon')
            ->orderBy('name')
            ->get(),
    ]);
});

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Password reset routes
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Guest to registered conversion
    Route::post('/convert-guest', [AuthController::class, 'convertGuestToRegistered']);

    // Streak routes
    Route::post('/streak', [StreakController::class, 'store']);
    
    // User discipline management
    Route::post('/user/disciplines/{discipline}', [AuthController::class, 'assignDiscipline']);
    Route::delete('/user/disciplines/{discipline}', [AuthController::class, 'removeDiscipline']);
    
    // User settings
    Route::patch('/user/settings', [AuthController::class, 'updateSettings']);

    // Admin routes (admin role required)
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::get('/users/{user}', [AdminUserController::class, 'show']);
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole']);
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy']);
        Route::get('/roles', [AdminUserController::class, 'roles']);
    });
});
