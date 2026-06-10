@extends('layout')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Adicionar curso</h1>

    <div class="bg-slate-800 rounded-xl p-6 shadow">
        <form method="POST" action="{{ route('courses.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Nome do curso</label>
                <input name="name" value="{{ old('name') }}" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" placeholder="Ex: Curso de JavaScript" />
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Progresso (%)</label>
                <input type="number" name="progress" value="{{ old('progress', 0) }}" min="0" max="100" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" />
                @error('progress')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Conteúdo (anotações/tópicos)</label>
                <textarea name="content" rows="10" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" placeholder="Digite o conteúdo do curso, organizado por tópicos...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-blue-500 dark:text-white dark:hover:bg-blue-600">Salvar</button>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-transparent dark:text-slate-300 dark:hover:text-white dark:border-slate-700">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection
