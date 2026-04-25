@extends('layout')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Editar hábito</h1>

    <div class="bg-slate-800 rounded-xl p-6 shadow">
        <form method="POST" action="{{ route('habits.update', $habit) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Nome do hábito</label>
                <input name="name" value="{{ old('name', $habit->name) }}" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" />
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-slate-300">Descrição (opcional)</label>
                <textarea name="description" class="w-full mt-2 p-2 rounded bg-slate-900 border border-slate-700" rows="3">{{ old('description', $habit->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Salvar</button>
                <a href="{{ route('habits.index') }}" class="text-slate-300 hover:text-white">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection
