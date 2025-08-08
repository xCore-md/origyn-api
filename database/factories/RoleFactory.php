<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->slug(),
            'display_name' => fake()->jobTitle(),
            'description' => fake()->sentence(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'System administrator with full access.',
        ]);
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'customer',
            'display_name' => 'Customer',
            'description' => 'Regular user with standard access.',
        ]);
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'guest',
            'display_name' => 'Guest',
            'description' => 'Guest user with limited access.',
        ]);
    }
}