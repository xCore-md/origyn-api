<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPassword = env('ADMIN_PASSWORD');
        
        if (!$adminPassword) {
            $this->command->error('ADMIN_PASSWORD not set in .env file');
            return;
        }
        
        User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Admin',
                'email' => 'contact@xcore.md',
                'password' => Hash::make($adminPassword),
                'is_guest' => false,
                'email_verified_at' => now(),
                'xp' => 0,
                'level_id' => null,
            ]
        );
        
        $this->command->info('Admin user created/updated successfully');
    }
}
