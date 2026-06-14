<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'content' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.string' => 'O título deve ser uma sequência de caracteres.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'title.not_regex' => 'O título não pode ficar em branco.',
        ];
    }
}
