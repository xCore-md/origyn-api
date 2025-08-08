<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Streak\StoreStreakRequest;
use App\Models\Streak;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StreakController extends Controller
{
    public function store(StoreStreakRequest $request): JsonResponse
    {
        $user = User::find(Auth::id());

        if ($user->streakToday()) {
            return response()->json([
                'success' => false,
                'message' => 'User already has a streak for today',
            ], 409);
        }

        Streak::create([
            'user_id' => $user->id,
        ]);

        return response()->json([], 201, [
            'success' => true,
            'message' => 'Streak created successfully',
        ]);
    }
}
