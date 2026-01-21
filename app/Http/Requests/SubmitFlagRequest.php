<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitFlagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flag' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'flag.required' => 'Please enter a flag.',
            'flag.max' => 'Flag is too long.',
        ];
    }
}
