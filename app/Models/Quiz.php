<?php

namespace App\Models;

use Database\Factories\QuizFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    /** @use HasFactory<QuizFactory> */
    use HasFactory;

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
