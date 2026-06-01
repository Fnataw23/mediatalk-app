<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['post_id', 'type', 'question_text'])]
class Task extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function answers()
    {
        return $this->hasMany(TaskAnswer::class);
    }

    public function completedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_tasks')
                    ->withTimestamps();
    }
}
