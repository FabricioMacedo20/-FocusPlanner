<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'total_pages' => 'required|integer|min:1',
            'current_page' => 'nullable|integer|min:0|lte:total_pages',
        ];
    }

    public function messages(): array
    {
        return [
            'book_title.required' => 'O título do livro é obrigatório.',
            'book_title.string' => 'O título do livro deve ser uma sequência de caracteres.',
            'book_title.max' => 'O título do livro não pode ter mais de 255 caracteres.',
            'book_title.not_regex' => 'O título do livro não pode ficar em branco.',
            'total_pages.required' => 'O total de páginas é obrigatório.',
            'total_pages.integer' => 'O total de páginas deve ser um número inteiro.',
            'total_pages.min' => 'O total de páginas deve ser pelo menos 1.',
            'current_page.integer' => 'As páginas lidas devem ser um número inteiro.',
            'current_page.min' => 'As páginas lidas não podem ser negativas.',
            'current_page.lte' => 'As páginas lidas não podem ser maiores que o total de páginas.',
        ];
    }
}
