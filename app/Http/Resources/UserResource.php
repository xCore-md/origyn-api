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
            'role' => [
                'id' => $this->role?->id,
                'name' => $this->role?->name,
                'display_name' => $this->role?->display_name,
            ],
            'progress_data' => $this->when($this->is_guest, $this->progress_data),
            'guest_token' => $this->when($this->is_guest, $this->guest_token),
            'streaks' => $this->weeklyStreaks(),
            'streaks_count' => $this->consecutiveStreakCount(),
            'has_streak_today' => $this->streakToday(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
