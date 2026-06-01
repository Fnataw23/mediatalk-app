<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'active', 'starts_at', 'ends_at'])]
class Debate extends Model
{
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function votes()
    {
        return $this->hasMany(DebateVote::class);
    }

    /**
     * Compute voting statistics on the fly.
     */
    public function getVoteStatsAttribute(): array
    {
        $total = $this->votes()->count();
        if ($total === 0) {
            return [
                'yes_percent' => 50,
                'no_percent' => 50,
                'yes_count' => 0,
                'no_count' => 0,
                'total' => 0
            ];
        }

        $yesCount = $this->votes()->where('vote', 'yes')->count();
        $noCount = $total - $yesCount;

        return [
            'yes_percent' => (int) round(($yesCount / $total) * 100),
            'no_percent' => (int) round(($noCount / $total) * 100),
            'yes_count' => $yesCount,
            'no_count' => $noCount,
            'total' => $total,
        ];
    }
}
