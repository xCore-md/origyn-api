<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_guest' => false,
            'guest_token' => null,
            'progress_data' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->afterCreating(function ($user) {
            $adminRole = Role::firstOrCreate(
                ['name' => 'admin'],
                ['display_name' => 'Administrator', 'description' => 'System administrator']
            );
            $user->update(['role_id' => $adminRole->id]);
        });
    }

    public function customer(): static
    {
        return $this->afterCreating(function ($user) {
            $customerRole = Role::firstOrCreate(
                ['name' => 'customer'],
                ['display_name' => 'Customer', 'description' => 'Regular user']
            );
            $user->update(['role_id' => $customerRole->id]);
        });
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Guest User',
            'email' => null,
            'email_verified_at' => null,
            'password' => null,
            'is_guest' => true,
            'guest_token' => Str::random(40),
            'progress_data' => [],
        ])->afterCreating(function ($user) {
            $guestRole = Role::firstOrCreate(
                ['name' => 'guest'],
                ['display_name' => 'Guest', 'description' => 'Guest user']
            );
            $user->update(['role_id' => $guestRole->id]);
        });
    }
}
