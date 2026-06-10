@extends('layout')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Adicionar leitura</h1>

    <div class="bg-slate-800 rounded-xl p-6 shadow">
        <form method="POST" action="{{ route('readings.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Título do livro</label>
                <input name="book_title" value="{{ old('book_title') }}" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" placeholder="Ex: O Poder do Hábito" />
                @error('book_title')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-slate-300">Total de páginas</label>
                    <input type="number" name="total_pages" value="{{ old('total_pages', 1) }}" min="1" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" />
                    @error('total_pages')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-slate-300">Páginas lidas</label>
                    <input type="number" name="current_page" value="{{ old('current_page', 0) }}" min="0" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" />
                    @error('current_page')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-blue-500 dark:text-white dark:hover:bg-blue-600">Salvar</button>
                <a href="{{ route('readings.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-transparent dark:text-slate-300 dark:hover:text-white dark:border-slate-700">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection
