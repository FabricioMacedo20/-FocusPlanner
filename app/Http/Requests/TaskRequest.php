<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255', 'not_regex:/^\s*$/'],
            'date' => ['required', 'date'],
            'priority' => ['required', 'in:baixa,media,alta'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.string' => 'O título deve ser uma sequência de caracteres.',
            'title.min' => 'O título deve ter pelo menos 3 caracteres.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'title.not_regex' => 'O título não pode ficar em branco.',
            'date.required' => 'Informe uma data válida.',
            'date.date' => 'Informe uma data válida.',
            'priority.required' => 'Selecione uma prioridade.',
            'priority.in' => 'Selecione uma prioridade válida.',
        ];
    }
}
