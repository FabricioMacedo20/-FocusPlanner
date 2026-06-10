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

        <!-- RESUMO DOS HÁBITOS -->
        @if(!$habits->isEmpty())
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-2xl p-6 shadow-md dark:shadow-lg border border-indigo-200 dark:border-indigo-700/50">
            <h2 class="text-lg font-bold text-indigo-900 dark:text-indigo-100 mb-4 flex items-center gap-2">
                📊 Resumo dos Hábitos
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-indigo-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Hábitos Ativos</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalActiveHabits }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-green-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Concluídos Hoje</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $completedCount }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-purple-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Taxa de Conclusão</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $completionRate }}%</p>
                </div>
            </div>
        </div>

        <!-- MENSAGEM MOTIVACIONAL -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800 flex items-start gap-3">
            <span class="text-2xl">💡</span>
            <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $motivationalMessage }}</p>
        </div>
        @endif

        <!-- Card principal - TABELA DE HÁBITOS -->
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
                                @php
                                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                                    $isCompletedToday = $habit->last_completed_at && $habit->last_completed_at->format('Y-m-d') === $today;
                                    $streakText = $habit->streak === 1 ? 'dia' : 'dias';
                                @endphp
                                <tr class="hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200">
                                    <td class="py-4 px-5 text-slate-900 dark:text-slate-100 font-medium">
                                        {{ $habit->name }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-bold inline-flex items-center gap-1">
                                            🔥 {{ $habit->streak }} {{ $streakText }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-slate-600 dark:text-slate-400 text-sm">
                                        {{ $habit->last_completed_at ? $habit->last_completed_at->format('d/m/Y') : '—' }}
                                    </td>
                                    <td class="py-4 px-5 space-x-3 text-sm">
                                        @if($isCompletedToday)
                                            <span class="inline-flex items-center px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg font-bold">✅ Concluído hoje</span>
                                        @else
                                            <a href="{{ route('habits.complete', $habit) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Marcar</a>
                                        @endif
                                        <a href="{{ route('habits.edit', $habit) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-white dark:border-slate-700">Editar</a>
                                        <form id="delete-habit-form-{{ $habit->id }}" class="inline" method="POST" action="{{ route('habits.destroy', $habit) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-delete-title="{{ $habit->name }}" data-form-id="delete-habit-form-{{ $habit->id }}" class="delete-confirm-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($habits->hasPages())
                    <div class="mt-6">
                        {{ $habits->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Seção Hábitos Completados Hoje -->
        @if($completedHabitsToday->isNotEmpty())
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                ✅ Hábitos Completados Hoje ({{ $completedHabitsToday->count() }})
            </h2>
            
            <div class="space-y-3">
                @foreach($completedHabitsToday as $index => $habit)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-start gap-3">
                            <span class="text-green-600 dark:text-green-400 font-bold text-lg">•</span>
                            <div>
                                <h3 class="font-semibold text-green-800 dark:text-green-200">{{ $habit->name }}</h3>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">🔥 {{ $habit->streak }} {{ $habit->streak === 1 ? 'dia' : 'dias' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-4 text-sm text-slate-700 dark:text-slate-300">
            <span class="font-semibold">ℹ️ Nota:</span> Esta seção permite acompanhar a evolução dos hábitos diários. A taxa de conclusão considera os hábitos realizados no dia em relação ao total de hábitos ativos. Exemplo: 2 hábitos concluídos de 2 ativos = 100%. Já a sequência indica a quantidade de dias consecutivos em que o hábito foi mantido.
        </div>

    </div>
</div>

@endsection
