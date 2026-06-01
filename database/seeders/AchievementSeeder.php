<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'title' => 'First Comment',
                'description' => 'Leave your first comment on any content block.',
                'code' => 'first_comment',
            ],
            [
                'title' => 'Discussion Starter',
                'description' => 'Write 10 comments and replies to keep the conversation going.',
                'code' => 'discussion_starter',
            ],
            [
                'title' => 'Movie Fan',
                'description' => 'Complete 20 interactive materials in the Movie Discussions category.',
                'code' => 'movie_fan',
            ],
            [
                'title' => 'Vocabulary Hunter',
                'description' => 'Acquire 100 new English words by completing educational content.',
                'code' => 'vocabulary_hunter',
            ],
            [
                'title' => 'Debater',
                'description' => 'Share your voice and cast a vote in 10 Weekly Debates.',
                'code' => 'debater',
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::updateOrCreate(['code' => $ach['code']], $ach);
        }
    }
}
