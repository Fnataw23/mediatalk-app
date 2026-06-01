<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['task_id', 'answer', 'is_correct', 'matching_translation'])]
class TaskAnswer extends Model
{
    protected $table = 'task_answers';

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
