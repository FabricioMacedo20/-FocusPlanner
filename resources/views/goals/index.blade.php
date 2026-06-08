@extends('layout')

@section('content')

    <div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 space-y-6 py-8">

        <x-page-header
            title="Metas"
            description="Defina objetivos e acompanhe seu progresso."
        >
            <a href="{{ route('goals.create') }}" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-slate-100 px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                Nova meta
            </a>
        </x-page-header>

        <!-- Card principal -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 overflow-hidden">
            @if($goals->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400 text-lg">Ainda não há metas ativas</p>
                    <p class="text-slate-400 dark:text-slate-500 text-sm mt-2">Crie uma nova meta para acompanhar seu progresso!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-light-bg dark:bg-slate-900 border-b-2 border-light-border dark:border-slate-700">
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Meta</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Progresso</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Status</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-light-border dark:divide-slate-700">
                            @foreach($goals as $goal)
                                @php
                                    $percent = $goal->target_value > 0 ? round(($goal->current_value / $goal->target_value) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200">
                                    <td class="py-4 px-5 text-slate-900 dark:text-slate-100 font-medium">
                                        {{ $goal->title }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-2">{{ $goal->current_value }} / {{ $goal->target_value }}</div>
                                        <div class="w-full bg-slate-200 dark:bg-slate-700 h-3 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 h-3 rounded-full transition-all duration-500" style="width: {{ min($percent, 100) }}%"></div>
                                        </div>
                                        <div class="text-xs text-slate-600 dark:text-slate-400 mt-2 font-bold">{{ $percent }}%</div>
                                    </td>
                                    <td class="py-4 px-5">
                                        @if($goal->status)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                ✅ Concluída
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                                ⏳ Em andamento
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 space-x-3 text-sm">
                                        <a href="{{ route('goals.edit', $goal) }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Editar</a>
                                        <a href="{{ route('goals.toggle-status', $goal) }}" class="inline-flex items-center px-4 py-2 {{ $goal->status ? 'bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600' : 'bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600' }} text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                                            @if($goal->status)
                                                Reabrir
                                            @else
                                                Concluir
                                            @endif
                                        </a>
                                        <form class="inline" method="POST" action="{{ route('goals.destroy', $goal) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700" onclick="return confirm('Tem certeza que deseja excluir esta meta?')">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Seção Concluídas -->
        @if($completedGoals->isNotEmpty())
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 mt-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                ✅ Metas Concluídas
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($completedGoals as $index => $goal)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-green-600 dark:text-green-400 font-bold">{{ $index + 1 }}.</span>
                        <h3 class="font-semibold text-green-800 dark:text-green-200">{{ $goal->title }}</h3>
                    </div>
                    <p class="text-sm text-green-600 dark:text-green-400">{{ $goal->current_value }} / {{ $goal->target_value }}</p>
                    <div class="text-xs text-green-500 dark:text-green-500 mt-1">Concluída em {{ $goal->updated_at->format('d/m/Y') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
