<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['post_id', 'word', 'transcription', 'translation', 'explanation', 'example'])]
class Vocabulary extends Model
{
    protected $table = 'vocabularies';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
