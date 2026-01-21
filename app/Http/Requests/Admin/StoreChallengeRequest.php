<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreChallengeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'flag' => ['required', 'string', 'max:255'],
            'points' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'exists:categories,id'],
            'challenge_file' => [
                'nullable',
                'file',
                'max:10240',
                'mimes:zip,txt,pdf,png,jpg,jpeg,tar,gz,py,c,cpp,java',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'challenge_file.max' => 'File size cannot exceed 10MB.',
            'challenge_file.mimes' => 'Only zip, txt, pdf, png, jpg, tar, gz, py, c, cpp, java files are allowed.',
        ];
    }
}
