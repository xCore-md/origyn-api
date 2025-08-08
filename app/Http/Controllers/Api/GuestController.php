<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function createGuest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'language_code' => 'nullable|string|exists:languages,code',
            'theme' => 'nullable|in:default,dark,light',
        ]);

        $guestToken = Str::random(40);
        $guestRole = Role::guest();
        
        // Get default language or use provided one
        $language = $validated['language_code'] 
            ? Language::findByCode($validated['language_code'])
            : Language::getDefault();

        $user = User::create([
            'name' => 'Guest User',
            'is_guest' => true,
            'guest_token' => $guestToken,
            'role_id' => $guestRole->id,
            'language_id' => $language?->id,
            'theme' => $validated['theme'] ?? 'default',
            'xp' => 0,
        ]);

        $token = $user->createToken('guest_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Guest user created successfully',
            'user' => new UserResource($user->load(['role', 'level', 'language'])),
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
            'user' => new UserResource($user->load(['role', 'level', 'language'])),
            'token' => $token,
        ]);
    }
}
