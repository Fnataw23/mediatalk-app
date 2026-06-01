<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['post_id', 'text'])]
class Question extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
