<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function createGuest(Request $request)
    {
        $guestToken = Str::random(40);
        
        $user = User::create([
            'name' => 'Guest User',
            'is_guest' => true,
            'guest_token' => $guestToken,
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

    public function authenticateGuest(Request $request)
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

    public function updateGuestData(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->is_guest) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid guest user',
            ], 401);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'progress_data' => 'sometimes|array',
        ]);

        if (isset($validatedData['name'])) {
            $user->name = $validatedData['name'];
        }

        if (isset($validatedData['progress_data'])) {
            $user->progress_data = $validatedData['progress_data'];
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Guest data updated successfully',
            'user' => $user,
        ]);
    }

    public function getGuestData(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->is_guest) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid guest user',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
            'progress_data' => $user->progress_data ?? [],
        ]);
    }

    public function deleteGuest(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->is_guest) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid guest user',
            ], 401);
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Guest user deleted successfully',
        ]);
    }
}