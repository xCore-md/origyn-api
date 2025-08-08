<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function admin()
    {
        return static::where('name', 'admin')->first();
    }

    public static function customer()
    {
        return static::where('name', 'customer')->first();
    }

    public static function guest()
    {
        return static::where('name', 'guest')->first();
    }

    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->name === 'customer';
    }

    public function isGuest(): bool
    {
        return $this->name === 'guest';
    }
}