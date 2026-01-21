<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreHintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'challenge_id' => ['required', 'exists:challenges,id'],
            'content' => ['required', 'string'],
            'cost' => ['required', 'integer', 'min:0'],
        ];
    }
}
