@extends('layout')

@section('content')

    <div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 space-y-6 py-8">

        <x-page-header
            title="Hábitos"
            description="Gerencie seus hábitos e acompanhe sua evolução diária."
        >
            <a href="{{ route('habits.create') }}" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-slate-100 px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                Novo hábito
            </a>
        </x-page-header>

        <!-- Card principal -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 overflow-hidden">
            @if($habits->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400 text-lg">Ainda não há hábitos</p>
                    <p class="text-slate-400 dark:text-slate-500 text-sm mt-2">Crie um novo hábito para começar sua jornada!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-light-bg dark:bg-slate-900 border-b-2 border-light-border dark:border-slate-700">
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Hábito</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Sequência</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Última vez</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-light-border dark:divide-slate-700">
                            @foreach($habits as $habit)
                                <tr class="hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200">
                                    <td class="py-4 px-5 text-slate-900 dark:text-slate-100 font-medium">
                                        {{ $habit->name }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-bold inline-flex items-center gap-1">
                                            🔥 {{ $habit->streak }} dias
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-slate-600 dark:text-slate-400 text-sm">
                                        {{ $habit->last_completed_at ? $habit->last_completed_at->format('d/m/Y') : '—' }}
                                    </td>
                                    <td class="py-4 px-5 space-x-3 text-sm">
                                        <a href="{{ route('habits.complete', $habit) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Marcar</a>
                                        <a href="{{ route('habits.edit', $habit) }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Editar</a>
                                        <form class="inline" method="POST" action="{{ route('habits.destroy', $habit) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700" onclick="return confirm('Tem certeza que deseja excluir este hábito?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Seção Hábitos Completados Hoje -->
        @if($completedHabitsToday->isNotEmpty())
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 mt-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                ✅ Hábitos Completados Hoje
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($completedHabitsToday as $index => $habit)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-green-600 dark:text-green-400 font-bold">{{ $index + 1 }}.</span>
                        <h3 class="font-semibold text-green-800 dark:text-green-200">{{ $habit->name }}</h3>
                    </div>
                    <p class="text-sm text-green-600 dark:text-green-400">Sequência: 🔥 {{ $habit->streak }} dias</p>
                    <div class="text-xs text-green-500 dark:text-green-500 mt-1">Completado hoje</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
