<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'level_number',
        'name',
        'description',
        'min_xp',
        'max_xp',
        'color',
        'icon',
    ];
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    public static function getLevelByXP(int $xp): ?Level
    {
        return self::where('min_xp', '<=', $xp)
            ->where('max_xp', '>=', $xp)
            ->first();
    }
    
    public function getProgressPercentage(int $currentXP): float
    {
        if ($currentXP < $this->min_xp) {
            return 0;
        }
        
        if ($currentXP > $this->max_xp) {
            return 100;
        }
        
        $range = $this->max_xp - $this->min_xp;
        $progress = $currentXP - $this->min_xp;
        
        return round(($progress / $range) * 100, 2);
    }
}
