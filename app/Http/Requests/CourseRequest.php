<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'progress' => 'required|integer|min:0|max:100',
            'content' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do curso é obrigatório.',
            'name.string' => 'O nome do curso deve ser uma sequência de caracteres.',
            'name.max' => 'O nome do curso não pode ter mais de 255 caracteres.',
            'name.not_regex' => 'O nome do curso não pode ficar em branco.',
            'progress.required' => 'O progresso é obrigatório.',
            'progress.integer' => 'O progresso deve ser um número inteiro.',
            'progress.min' => 'O progresso deve ser no mínimo 0%.',
            'progress.max' => 'O progresso não pode ultrapassar 100%.',
            'content.max' => 'O conteúdo não pode ultrapassar 2000 caracteres.',
        ];
    }
}
