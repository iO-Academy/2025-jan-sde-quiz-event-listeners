<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quiz' => 'required|integer|exists:quizzes,id',
            'answers' => 'required|array',
            'answers.*.question' => 'required|integer|exists:questions,id',
            'answers.*.answer' => 'required|integer|exists:answers,id',
        ];
    }
}
