<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'key' => 'first_streak',
                'name' => 'First Steps',
                'description' => 'Complete your first daily streak',
                'icon' => '🌱',
                'color' => '#10B981',
                'xp_reward' => 25,
                'type' => 'streak',
                'criteria' => ['streak_count' => 1],
                'order' => 1,
            ],
            [
                'key' => '3_day_streak',
                'name' => '3-Day Warrior',
                'description' => 'Maintain a streak for 3 consecutive days',
                'icon' => '🔥',
                'color' => '#F59E0B',
                'xp_reward' => 50,
                'type' => 'streak',
                'criteria' => ['streak_count' => 3],
                'order' => 2,
            ],
            [
                'key' => 'week_warrior',
                'name' => 'Week Warrior',
                'description' => 'Complete a full week of daily streaks',
                'icon' => '⚡',
                'color' => '#3B82F6',
                'xp_reward' => 100,
                'type' => 'streak',
                'criteria' => ['streak_count' => 7],
                'order' => 3,
            ],
            [
                'key' => 'month_master',
                'name' => 'Month Master',
                'description' => 'Maintain a streak for 30 consecutive days',
                'icon' => '👑',
                'color' => '#8B5CF6',
                'xp_reward' => 300,
                'type' => 'streak',
                'criteria' => ['streak_count' => 30],
                'order' => 4,
            ],
            [
                'key' => 'level_up_2',
                'name' => 'Rising Star',
                'description' => 'Reach level 2',
                'icon' => '⭐',
                'color' => '#6B7280',
                'xp_reward' => 50,
                'type' => 'level',
                'criteria' => ['level' => 2],
                'order' => 5,
            ],
            [
                'key' => 'level_up_5',
                'name' => 'Expert Status',
                'description' => 'Reach level 5 (Expert)',
                'icon' => '🎯',
                'color' => '#8B5CF6',
                'xp_reward' => 200,
                'type' => 'level',
                'criteria' => ['level' => 5],
                'order' => 6,
            ],
            [
                'key' => 'level_up_10',
                'name' => 'Divine Ascension',
                'description' => 'Reach the ultimate level 10 (Divine)',
                'icon' => '🌟',
                'color' => '#F97316',
                'xp_reward' => 1000,
                'type' => 'level',
                'criteria' => ['level' => 10],
                'order' => 7,
            ],
            [
                'key' => 'consistency_king',
                'name' => 'Consistency King',
                'description' => 'Complete 100 total streaks',
                'icon' => '💎',
                'color' => '#EC4899',
                'xp_reward' => 500,
                'type' => 'general',
                'criteria' => ['total_streaks' => 100],
                'order' => 8,
            ],
            [
                'key' => 'xp_collector',
                'name' => 'XP Collector',
                'description' => 'Earn 1000 total experience points',
                'icon' => '💰',
                'color' => '#F59E0B',
                'xp_reward' => 100,
                'type' => 'general',
                'criteria' => ['total_xp' => 1000],
                'order' => 9,
            ],
            [
                'key' => 'early_bird',
                'name' => 'Early Bird',
                'description' => 'Complete 10 streaks before noon',
                'icon' => '🐦',
                'color' => '#10B981',
                'xp_reward' => 150,
                'type' => 'general',
                'criteria' => ['early_streaks' => 10],
                'order' => 10,
                'is_hidden' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::firstOrCreate(
                ['key' => $achievement['key']],
                $achievement
            );
        }
    }
}
