<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['name', 'email', 'password', 'english_level', 'xp', 'avatar', 'is_admin', 'is_blocked'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_blocked' => 'boolean',
            'xp' => 'integer',
        ];
    }

    /**
     * Calculate Level dynamically based on XP.
     * Level = floor(sqrt(XP / 50)) + 1
     */
    public function getLevelAttribute(): int
    {
        return (int) floor(sqrt($this->xp / 50)) + 1;
    }

    /**
     * Get target XP for the next level.
     */
    public function getXpForNextLevelAttribute(): int
    {
        $nextLevel = $this->level; // Level index starts at 1, so L is the next level index
        return 50 * pow($nextLevel, 2);
    }

    /**
     * Get min XP for the current level.
     */
    public function getXpForCurrentLevelAttribute(): int
    {
        $currentLevel = $this->level;
        return 50 * pow($currentLevel - 1, 2);
    }

    /**
     * Get percentage progress to the next level (0 - 100).
     */
    public function getXpProgressAttribute(): int
    {
        $minXp = $this->xp_for_current_level;
        $maxXp = $this->xp_for_next_level;
        
        $totalForLevel = $maxXp - $minXp;
        if ($totalForLevel <= 0) return 100;
        
        $earnedInLevel = $this->xp - $minXp;
        
        return (int) min(100, max(0, round(($earnedInLevel / $totalForLevel) * 100)));
    }

    // Relationships

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('created_at');
    }

    public function debateVotes()
    {
        return $this->hasMany(DebateVote::class);
    }

    public function completedTasks()
    {
        return $this->belongsToMany(Task::class, 'user_tasks')
                    ->withTimestamps();
    }
}
