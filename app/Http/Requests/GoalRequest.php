<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'description' => 'nullable|string',
            'target_value' => 'required|integer|min:1',
            'current_value' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título da meta é obrigatório.',
            'title.string' => 'O título da meta deve ser uma sequência de caracteres.',
            'title.max' => 'O título da meta não pode ter mais de 255 caracteres.',
            'title.not_regex' => 'O título da meta não pode ficar em branco.',
            'target_value.required' => 'O valor alvo é obrigatório.',
            'target_value.integer' => 'O valor alvo deve ser um número inteiro.',
            'target_value.min' => 'O valor alvo deve ser pelo menos 1.',
            'current_value.integer' => 'O progresso atual deve ser um número inteiro.',
            'current_value.min' => 'O progresso atual não pode ser negativo.',
        ];
    }
}
