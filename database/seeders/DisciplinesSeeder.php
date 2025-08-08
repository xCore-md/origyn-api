<?php

namespace Database\Seeders;

use App\Models\Discipline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisciplinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disciplines = [
            ['name' => 'Music', 'is_active' => true, 'icon' => '🎵'],
            ['name' => 'Writing', 'is_active' => true, 'icon' => '✍️'],
            ['name' => 'Design', 'is_active' => true, 'icon' => '🎨'],
            ['name' => 'Visual Art', 'is_active' => true, 'icon' => '🖼️'],
            ['name' => 'Photography', 'is_active' => true, 'icon' => '📸'],
            ['name' => 'Coding', 'is_active' => true, 'icon' => '💻'],
        ];

        foreach ($disciplines as $discipline) {
            Discipline::firstOrCreate(['name' => $discipline['name']], $discipline);
        }
    }
}
