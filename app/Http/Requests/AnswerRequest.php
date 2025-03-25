<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'answer' => 'required|string|max:255',
            'correct' => 'required|boolean',
            'feedback' => 'nullable|string|max:255',
        ];

        if($this->isMethod('POST')) {
            $rules['question_id'] = 'required|integer|exists:questions,id';
        }

        return $rules;
    }
}
