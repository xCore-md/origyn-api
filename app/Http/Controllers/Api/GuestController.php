<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function createGuest(Request $request): JsonResponse
    {
        $guestToken = Str::random(40);
        $guestRole = Role::guest();

        $user = User::create([
            'name' => 'Guest User',
            'is_guest' => true,
            'guest_token' => $guestToken,
            'role_id' => $guestRole->id,
            'xp' => 0,
            'level' => 1,
        ]);

        $token = $user->createToken('guest_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Guest user created successfully',
            'user' => $user,
            'token' => $token,
            'guest_token' => $guestToken,
        ], 201);
    }

    public function authenticateGuest(Request $request): JsonResponse
    {
        $request->validate([
            'guest_token' => 'required|string',
        ]);

        $user = User::where('guest_token', $request->guest_token)
                   ->where('is_guest', true)
                   ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid guest token',
            ], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('guest_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Guest authenticated successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }
}
