<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditQuizRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|required|max:255|min:2',
            'description' => 'string|required',
        ];
    }
}
