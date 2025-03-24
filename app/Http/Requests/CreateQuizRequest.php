<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuizRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|required|max:255',
            'description' => 'string|required',
        ];
    }
}
