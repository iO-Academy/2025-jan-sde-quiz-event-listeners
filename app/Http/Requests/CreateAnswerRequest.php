<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAnswerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'answer' => 'required|string',
            'correct' => 'required|boolean',
            'question_id' => 'required|integer|exists:questions,id',
            'feedback' => 'nullable|string',
        ];
    }
}
