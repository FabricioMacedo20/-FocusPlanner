@extends('layout')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Editar leitura</h1>

    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm dark:shadow-lg border border-slate-200 dark:border-slate-700">
        <form method="POST" action="{{ route('readings.update', $reading) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm text-slate-900 dark:text-slate-300">Título do livro</label>
                <input name="book_title" value="{{ old('book_title', $reading->book_title) }}" class="w-full mt-2 p-2 rounded bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:border-slate-700 dark:focus:ring-blue-800" />
                @error('book_title')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-slate-900 dark:text-slate-300">Total de páginas</label>
                    <input type="number" name="total_pages" value="{{ old('total_pages', $reading->total_pages) }}" min="1" class="w-full mt-2 p-2 rounded bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:border-slate-700 dark:focus:ring-blue-800" />
                    @error('total_pages')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-slate-900 dark:text-slate-300">Páginas lidas</label>
                    <input type="number" name="current_page" value="{{ old('current_page', $reading->current_page) }}" min="0" class="w-full mt-2 p-2 rounded bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:border-slate-700 dark:focus:ring-blue-800" />
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
