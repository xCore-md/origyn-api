<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_guest' => $this->is_guest,
            'guest_token' => $this->when($this->is_guest, $this->guest_token),
            'streaks' => $this->weeklyStreaks(),
            'streaks_count' => $this->consecutiveStreakCount(),
            'has_streak_today' => $this->streakToday(),
            'level' => $this->getLevelProgress(),
            'achievements' => [
                'unlocked' => $this->achievements->map(fn($achievement) => [
                    'id' => $achievement->id,
                    'key' => $achievement->key,
                    'name' => $achievement->name,
                    'description' => $achievement->description,
                    'icon' => $achievement->icon,
                    'color' => $achievement->color,
                    'xp_reward' => $achievement->xp_reward,
                    'type' => $achievement->type,
                    'unlocked_at' => $achievement->pivot->unlocked_at,
                ]),
                'total_unlocked' => $this->achievements->count(),
                'total_xp_from_achievements' => $this->achievements->sum('xp_reward'),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
