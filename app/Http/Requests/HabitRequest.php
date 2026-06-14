<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HabitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do hábito é obrigatório.',
            'name.string' => 'O nome do hábito deve ser uma sequência de caracteres.',
            'name.max' => 'O nome do hábito não pode ter mais de 255 caracteres.',
            'name.not_regex' => 'O nome do hábito não pode ficar em branco.',
        ];
    }
}
