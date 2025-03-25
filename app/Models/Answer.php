<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    /** @use HasFactory<\Database\Factories\AnswerFactory> */
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at', 'question_id'];

    private static function find(int $id) {}

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
