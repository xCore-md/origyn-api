<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Support\Facades\Route;

// Guest routes (no authentication required)
Route::post('/guest/create', [GuestController::class, 'createGuest']);
Route::post('/guest/auth', [GuestController::class, 'authenticateGuest']);

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
    
    // Guest data management
    Route::get('/guest/data', [GuestController::class, 'getGuestData']);
    Route::put('/guest/data', [GuestController::class, 'updateGuestData']);
    Route::delete('/guest', [GuestController::class, 'deleteGuest']);
});
