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

        $xp = 10;

        $consecutiveStreaks = $user->consecutiveStreakCount();
        if ($consecutiveStreaks % 7 === 0 && $consecutiveStreaks > 0) {
            $xp = 50;
        }

        $user->addXP($xp);

        $newAchievements = $user->checkAchievements();

        return response()->json([
            'xp' => $xp,
            'streaks_count' => $user->consecutiveStreakCount(),
            'new_achievements' => $newAchievements->map(fn($achievement) => [
                'id' => $achievement->id,
                'key' => $achievement->key,
                'name' => $achievement->name,
                'description' => $achievement->description,
                'icon' => $achievement->icon,
                'color' => $achievement->color,
                'xp_reward' => $achievement->xp_reward,
            ]),
            'success' => true,
            'message' => 'Streak created successfully',
        ], 201);
    }
}
