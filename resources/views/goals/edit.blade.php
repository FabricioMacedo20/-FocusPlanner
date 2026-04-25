@extends('layout')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Editar meta</h1>

    <div class="bg-slate-800 rounded-xl p-6 shadow">
        <form method="POST" action="{{ route('goals.update', $goal) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Título</label>
                <input name="title" value="{{ old('title', $goal->title) }}" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" />
                @error('title')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Descrição (opcional)</label>
                <textarea name="description" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" rows="3">{{ old('description', $goal->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-slate-300">Valor alvo</label>
                    <input type="number" name="target_value" value="{{ old('target_value', $goal->target_value) }}" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" />
                    @error('target_value')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-slate-300">Progresso atual</label>
                    <input type="number" name="current_value" value="{{ old('current_value', $goal->current_value) }}" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" />
                    @error('current_value')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Salvar</button>
                <a href="{{ route('goals.index') }}" class="text-slate-300 hover:text-white">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection
