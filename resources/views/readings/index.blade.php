@extends('layout')

@section('content')

    <div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 space-y-6 py-8">

        <x-page-header
            title="Leitura"
            description="Registre suas leituras e acompanhe seu progresso."
        >
            <a href="{{ route('readings.create') }}" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-slate-100 px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                Novo livro
            </a>
        </x-page-header>

        <!-- Card principal -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 overflow-hidden">
            @if($readings->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400 text-lg">Nenhum livro registrado</p>
                    <p class="text-slate-400 dark:text-slate-500 text-sm mt-2">Adicione um livro para acompanhar seu progresso de leitura!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-light-bg dark:bg-slate-900 border-b-2 border-light-border dark:border-slate-700">
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Livro</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Progresso</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-light-border dark:divide-slate-700">
                            @foreach($readings as $reading)
                                @php
                                    $percent = $reading->total_pages > 0 ? round(($reading->current_page / $reading->total_pages) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200">
                                    <td class="py-4 px-5 text-slate-900 dark:text-slate-100 font-medium">
                                        {{ $reading->book_title }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-2">{{ $reading->current_page }} / {{ $reading->total_pages }}</div>
                                        <div class="w-full bg-slate-200 dark:bg-slate-700 h-3 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 h-3 rounded-full transition-all duration-500" style="width: {{ min($percent, 100) }}%"></div>
                                        </div>
                                        <div class="text-xs text-slate-600 dark:text-slate-400 mt-2 font-bold">{{ $percent }}%</div>
                                    </td>
                                    <td class="py-4 px-5 space-x-3 text-sm">
                                        @if(!$reading->completed)
                                            <a href="{{ route('readings.complete', $reading) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Concluído</a>
                                        @endif
                                        <a href="{{ route('readings.edit', $reading) }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Editar</a>
                                        <form class="inline" method="POST" action="{{ route('readings.destroy', $reading) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700" onclick="return confirm('Tem certeza que deseja excluir este registro de leitura?')">
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

        <!-- Seção Concluídos -->
        @if($completedReadings->isNotEmpty())
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 mt-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                ✅ Concluídos
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($completedReadings as $reading)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800 dark:text-green-200 mb-2">{{ $reading->book_title }}</h3>
                    <p class="text-sm text-green-600 dark:text-green-400">{{ $reading->current_page }} / {{ $reading->total_pages }} páginas</p>
                    <div class="text-xs text-green-500 dark:text-green-500 mt-1">Concluído em {{ $reading->updated_at->format('d/m/Y') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
