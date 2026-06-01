<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Achievements and Posts
        $this->call([
            AchievementSeeder::class,
            PostSeeder::class,
        ]);

        // Create Default Administrator
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@mediatalk.com',
            'password' => bcrypt('secret'),
            'is_admin' => true,
            'english_level' => 'B2',
            'xp' => 500, // Starts at Level 4
        ]);

        // Create Default Regular User
        User::factory()->create([
            'name' => 'Mikhail',
            'email' => 'user@mediatalk.com',
            'password' => bcrypt('secret'),
            'is_admin' => false,
            'english_level' => 'B1',
            'xp' => 10,
        ]);
    }
}
