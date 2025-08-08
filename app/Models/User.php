<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
