@extends('layout')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Editar curso</h1>

    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm dark:shadow-lg border border-slate-200 dark:border-slate-700">
        <form method="POST" action="{{ route('courses.update', $course) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm text-slate-900 dark:text-slate-300">Nome do curso</label>
                <input name="name" value="{{ old('name', $course->name) }}" class="w-full mt-2 p-2 rounded bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:border-slate-700 dark:focus:ring-blue-800" />
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-slate-900 dark:text-slate-300">Progresso (%)</label>
                <input type="number" name="progress" value="{{ old('progress', $course->progress) }}" min="0" max="100" class="w-full mt-2 p-2 rounded bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:border-slate-700 dark:focus:ring-blue-800" />
                @error('progress')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-slate-900 dark:text-slate-300">Conteúdo (anotações/tópicos)</label>
                <textarea name="content" rows="10" class="w-full mt-2 p-2 rounded bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:border-slate-700 dark:focus:ring-blue-800" placeholder="Digite o conteúdo do curso, organizado por tópicos...">{{ old('content', $course->content) }}</textarea>
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
