<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\Post;
use App\Models\Vocabulary;
use Illuminate\Support\Facades\Session;

class XPService
{
    /**
     * Award XP to a user and check for achievements.
     */
    public static function awardXp(User $user, int $amount, string $reason = ''): void
    {
        if ($amount <= 0) {
            return;
        }

        $oldLevel = $user->level;
        $user->xp += $amount;
        $user->save();

        $newLevel = $user->level;

        // Flash message for XP gained
        $gainedMsg = "+{$amount} XP: {$reason}";
        Session::flash('xp_gained', $gainedMsg);

        // Flash message for Level Up if user leveled up
        if ($newLevel > $oldLevel) {
            Session::flash('level_up', "🎉 LEVEL UP! You reached Level {$newLevel}!");
        }

        // Run achievements check
        self::checkAchievements($user);
    }

    /**
     * Check and award achievements to a user.
     */
    public static function checkAchievements(User $user): void
    {
        // 1. First Comment (first_comment)
        $commentCount = $user->comments()->count();
        if ($commentCount >= 1) {
            self::unlockAchievement($user, 'first_comment');
        }

        // 2. Discussion Starter (discussion_starter)
        if ($commentCount >= 10) {
            self::unlockAchievement($user, 'discussion_starter');
        }

        // 3. Movie Fan (movie_fan) - Complete 20 materials (posts) from Movie Discussions category
        // A material is completed when the user has solved any task belonging to it
        $moviePostsCount = Post::whereHas('category', function ($q) {
            $q->where('slug', 'movie-discussions');
        })->whereHas('tasks.completedByUsers', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->count();

        if ($moviePostsCount >= 20) {
            self::unlockAchievement($user, 'movie_fan');
        }

        // 4. Vocabulary Hunter (vocabulary_hunter) - Learn 100 words
        // Calculated as total vocabulary words in posts completed by the user
        $studiedWordsCount = Vocabulary::whereHas('post.tasks.completedByUsers', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->count();

        if ($studiedWordsCount >= 100) {
            self::unlockAchievement($user, 'vocabulary_hunter');
        }

        // 5. Debater (debater) - Participate in 10 debates
        $debatesVotedCount = $user->debateVotes()->count();
        if ($debatesVotedCount >= 10) {
            self::unlockAchievement($user, 'debater');
        }
    }

    /**
     * Unlock an achievement for a user if they don't have it yet.
     */
    private static function unlockAchievement(User $user, string $code): void
    {
        $achievement = Achievement::where('code', $code)->first();

        if (!$achievement) {
            return;
        }

        // Check if already unlocked
        if (!$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
            $user->achievements()->attach($achievement->id);
            Session::flash('achievement_unlocked', "🏆 Achievement Unlocked: **{$achievement->title}** - {$achievement->description}");
        }
    }
}
