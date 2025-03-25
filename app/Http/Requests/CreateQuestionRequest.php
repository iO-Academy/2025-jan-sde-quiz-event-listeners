<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => 'required|string|min:5|max:70',
            'hint' => 'nullable|string|min:5|max:70',
            'points' => 'required|integer',
            'quiz_id' => 'required|integer|exists:quizzes,id',
        ];
    }
}
