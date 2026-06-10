@extends('layout')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Criar hábito</h1>

    <div class="bg-slate-800 rounded-xl p-6 shadow">
        <form method="POST" action="{{ route('habits.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Nome do hábito</label>
                <input name="name" value="{{ old('name') }}" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" placeholder="Ex: Ler 15 minutos" />
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Descrição (opcional)</label>
                <textarea name="description" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-blue-500 dark:text-white dark:hover:bg-blue-600">Salvar</button>
                <a href="{{ route('habits.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-transparent dark:text-slate-300 dark:hover:text-white dark:border-slate-700">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection
