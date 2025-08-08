<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_guest',
        'guest_token',
        'progress_data',
        'role_id',
        'xp',
        'level_id',
        'language_id',
        'theme',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_guest' => 'boolean',
            'progress_data' => 'array',
        ];
    }
    
    public function scopeGuests($query)
    {
        return $query->where('is_guest', true);
    }
    
    public function scopeRegistered($query)
    {
        return $query->where('is_guest', false);
    }
    
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
    
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }
    
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
    
    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }
    
    public function isGuestRole(): bool
    {
        return $this->hasRole('guest');
    }
    
    public function streaks(): HasMany
    {
        return $this->hasMany(Streak::class);
    }
    
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withTimestamps()
            ->withPivot('unlocked_at')
            ->orderBy('unlocked_at', 'desc');
    }
    
    public function streakToday(): bool
    {
        return $this->streaks()
            ->whereDate('created_at', today())
            ->exists();
    }
    
    public function consecutiveStreakCount(): int
    {
        $streaks = $this->streaks()
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();
        
        if (empty($streaks)) {
            return 0;
        }
        
        $count = 0;
        $currentDate = today()->format('Y-m-d');
        
        foreach ($streaks as $streakDate) {
            if ($streakDate === $currentDate) {
                $count++;
                $currentDate = today()->subDays($count)->format('Y-m-d');
            } else {
                break;
            }
        }
        
        return $count;
    }
    
    public function weeklyStreaks(): array
    {
        $streakDates = $this->streaks()
            ->whereBetween('created_at', [today()->subDays(6), today()->endOfDay()])
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->pluck('date')
            ->toArray();
        
        $weeklyStreaks = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $dayName = strtolower($date->format('l'));
            $dateString = $date->format('Y-m-d');
            
            $weeklyStreaks[$dayName] = in_array($dateString, $streakDates);
        }
        
        return $weeklyStreaks;
    }
    
    public function addXP(int $amount): void
    {
        $this->increment('xp', $amount);
        $this->updateLevel();
    }
    
    public function updateLevel(): void
    {
        $newLevel = Level::getLevelByXP($this->xp);
        
        if ($newLevel && $this->level_id !== $newLevel->id) {
            $this->update(['level_id' => $newLevel->id]);
        }
    }
    
    public function getCurrentLevel(): ?Level
    {
        return $this->level ?? Level::getLevelByXP($this->xp ?? 0);
    }
    
    public function getLevelProgress(): array
    {
        $currentLevel = $this->getCurrentLevel();
        
        if (!$currentLevel) {
            return [
                'current_xp' => $this->xp ?? 0,
                'level_name' => 'Unranked',
                'level_number' => 0,
                'progress_percentage' => 0,
                'xp_to_next_level' => 100,
            ];
        }
        
        $nextLevel = Level::where('level_number', '>', $currentLevel->level_number)
            ->orderBy('level_number')
            ->first();
        
        return [
            'current_xp' => $this->xp ?? 0,
            'level_name' => $currentLevel->name,
            'level_number' => $currentLevel->level_number,
            'level_color' => $currentLevel->color,
            'level_icon' => $currentLevel->icon,
            'progress_percentage' => $currentLevel->getProgressPercentage($this->xp ?? 0),
            'xp_to_next_level' => $nextLevel ? $nextLevel->min_xp - ($this->xp ?? 0) : 0,
            'next_level_name' => $nextLevel?->name,
        ];
    }
    
    public function hasAchievement(string $achievementKey): bool
    {
        return $this->achievements()->where('key', $achievementKey)->exists();
    }
    
    public function unlockAchievement(Achievement $achievement): bool
    {
        if ($this->hasAchievement($achievement->key)) {
            return false;
        }
        
        $this->achievements()->attach($achievement->id, [
            'unlocked_at' => now(),
        ]);
        
        $this->addXP($achievement->xp_reward);
        
        return true;
    }
    
    public function getTotalStreaks(): int
    {
        return $this->streaks()->count();
    }
    
    public function getEarlyStreaks(): int
    {
        return $this->streaks()
            ->whereRaw('TIME(created_at) < ?', ['12:00:00'])
            ->count();
    }
    
    public function checkAchievements(): array
    {
        $unlockedAchievements = [];
        $achievements = Achievement::all();
        
        foreach ($achievements as $achievement) {
            if ($this->hasAchievement($achievement->key)) {
                continue;
            }
            
            if ($this->meetsAchievementCriteria($achievement)) {
                if ($this->unlockAchievement($achievement)) {
                    $unlockedAchievements[] = $achievement;
                }
            }
        }
        
        return $unlockedAchievements;
    }
    
    private function meetsAchievementCriteria(Achievement $achievement): bool
    {
        $criteria = $achievement->criteria;
        
        if (!$criteria) {
            return false;
        }
        
        foreach ($criteria as $key => $requiredValue) {
            $currentValue = match ($key) {
                'streak_count' => $this->consecutiveStreakCount(),
                'level' => $this->getCurrentLevel()?->level_number ?? 1,
                'total_streaks' => $this->getTotalStreaks(),
                'total_xp' => $this->xp ?? 0,
                'early_streaks' => $this->getEarlyStreaks(),
                default => 0,
            };
            
            if ($currentValue < $requiredValue) {
                return false;
            }
        }
        
        return true;
    }
}
