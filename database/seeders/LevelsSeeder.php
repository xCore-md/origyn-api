<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['level_number' => 1, 'name' => 'Novice', 'min_xp' => 0, 'max_xp' => 99, 'color' => '#9CA3AF', 'icon' => '🌱'],
            ['level_number' => 2, 'name' => 'Beginner', 'min_xp' => 100, 'max_xp' => 249, 'color' => '#6B7280', 'icon' => '🌿'],
            ['level_number' => 3, 'name' => 'Amateur', 'min_xp' => 250, 'max_xp' => 499, 'color' => '#10B981', 'icon' => '🌳'],
            ['level_number' => 4, 'name' => 'Enthusiast', 'min_xp' => 500, 'max_xp' => 999, 'color' => '#3B82F6', 'icon' => '💪'],
            ['level_number' => 5, 'name' => 'Expert', 'min_xp' => 1000, 'max_xp' => 1999, 'color' => '#8B5CF6', 'icon' => '🎯'],
            ['level_number' => 6, 'name' => 'Master', 'min_xp' => 2000, 'max_xp' => 3999, 'color' => '#F59E0B', 'icon' => '👑'],
            ['level_number' => 7, 'name' => 'Grandmaster', 'min_xp' => 4000, 'max_xp' => 7999, 'color' => '#EF4444', 'icon' => '🔥'],
            ['level_number' => 8, 'name' => 'Legend', 'min_xp' => 8000, 'max_xp' => 15999, 'color' => '#7C3AED', 'icon' => '⚡'],
            ['level_number' => 9, 'name' => 'Mythic', 'min_xp' => 16000, 'max_xp' => 31999, 'color' => '#EC4899', 'icon' => '💎'],
            ['level_number' => 10, 'name' => 'Divine', 'min_xp' => 32000, 'max_xp' => 999999, 'color' => '#F97316', 'icon' => '🌟'],
        ];
        
        foreach ($levels as $level) {
            Level::firstOrCreate(
                ['level_number' => $level['level_number']],
                $level
            );
        }
    }
}
