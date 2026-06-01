<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'debate_id', 'vote'])]
class DebateVote extends Model
{
    protected $table = 'debate_votes';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function debate()
    {
        return $this->belongsTo(Debate::class);
    }
}
