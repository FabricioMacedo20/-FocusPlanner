@extends('layout')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    <!-- Cabeçalho com título e descrição -->
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/40 dark:to-blue-900/40 rounded-lg p-8 shadow-md border border-purple-200 dark:border-slate-700">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100">📈 Relatório de Desempenho</h1>
        <p class="text-slate-600 dark:text-slate-400 mt-2">Acompanhe sua produtividade semanal, mensal e anual</p>
    </div>

    <!-- ==================== DESEMPENHO SEMANAL ==================== -->
    <div class="bg-light-card dark:bg-slate-800 rounded-lg p-8 shadow-md border border-light-border dark:border-slate-700">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">📅 Desempenho Semanal</h2>
            <span class="text-sm text-slate-500">{{ $weeklyPerformance['startDate'] }} - {{ $weeklyPerformance['endDate'] }}</span>
        </div>

        <!-- Grid: Percentual + Dados -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Card: Círculo de Percentual -->
            <div class="flex items-center justify-center">
                <div class="relative w-48 h-48">
                    <!-- Círculo de fundo -->
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 200 200">
                        <!-- Círculo vazio -->
                        <circle cx="100" cy="100" r="90" fill="none" stroke="#e5e7eb" stroke-width="8" class="dark:stroke-slate-700"/>
                        
                        <!-- Círculo preeenchido (progresso) -->
                        @if ($weeklyPerformance['color'] === 'red')
                            <circle cx="100" cy="100" r="90" fill="none" stroke="#ef4444" stroke-width="8" 
                                    stroke-dasharray="{{ ($weeklyPerformance['percentage'] / 100) * 565.48 }}, 565.48"
                                    stroke-linecap="round"/>
                        @elseif ($weeklyPerformance['color'] === 'yellow')
                            <circle cx="100" cy="100" r="90" fill="none" stroke="#f59e0b" stroke-width="8" 
                                    stroke-dasharray="{{ ($weeklyPerformance['percentage'] / 100) * 565.48 }}, 565.48"
                                    stroke-linecap="round"/>
                        @else
                            <circle cx="100" cy="100" r="90" fill="none" stroke="#10b981" stroke-width="8" 
                                    stroke-dasharray="{{ ($weeklyPerformance['percentage'] / 100) * 565.48 }}, 565.48"
                                    stroke-linecap="round"/>
                        @endif
                    </svg>

                    <!-- Texto no centro -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <p class="text-5xl font-bold text-slate-900 dark:text-slate-100">
                            {{ $weeklyPerformance['percentage'] }}%
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                            {{ $weeklyPerformance['classification'] }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card: Detalhes -->
            <div class="space-y-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/40 dark:to-slate-800 rounded-lg p-6 border border-blue-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Tarefas Concluídas</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['completedTasks'] }}/{{ $weeklyPerformance['totalTasks'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/40 dark:to-slate-800 rounded-lg p-6 border border-green-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Hábitos Completados</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['completedHabits'] }}/{{ $weeklyPerformance['totalHabits'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/40 dark:to-slate-800 rounded-lg p-6 border border-purple-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Metas Concluídas</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['completedGoals'] }}/{{ $weeklyPerformance['totalGoals'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/40 dark:to-slate-800 rounded-lg p-6 border border-amber-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Cursos Ativos</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['totalCourses'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/40 dark:to-slate-800 rounded-lg p-6 border border-indigo-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Leituras Ativas</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['totalReadings'] }}</p>
                </div>
            </div>

        </div>

    </div>

    <!-- ==================== DESEMPENHO MENSAL ==================== -->
    <div class="bg-light-card dark:bg-slate-800 rounded-lg p-8 shadow-md border border-light-border dark:border-slate-700">
        
        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">📆 Desempenho do Mês ({{ $monthlyPerformance['month'] }})</h2>

        <!-- Grid: Percentual + Gráfico de semanas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Card Principal: Percentual do Mês -->
            <div class="flex items-center justify-center">
                <div class="relative w-48 h-48">
                    <!-- Círculo de fundo -->
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 200 200">
                        <!-- Círculo vazio -->
                        <circle cx="100" cy="100" r="90" fill="none" stroke="#e5e7eb" stroke-width="8" class="dark:stroke-slate-700"/>
                        
                        <!-- Círculo preeenchido (progresso) -->
                        @if ($monthlyPerformance['color'] === 'red')
                            <circle cx="100" cy="100" r="90" fill="none" stroke="#ef4444" stroke-width="8" 
                                    stroke-dasharray="{{ ($monthlyPerformance['percentage'] / 100) * 565.48 }}, 565.48"
                                    stroke-linecap="round"/>
                        @elseif ($monthlyPerformance['color'] === 'yellow')
                            <circle cx="100" cy="100" r="90" fill="none" stroke="#f59e0b" stroke-width="8" 
                                    stroke-dasharray="{{ ($monthlyPerformance['percentage'] / 100) * 565.48 }}, 565.48"
                                    stroke-linecap="round"/>
                        @else
                            <circle cx="100" cy="100" r="90" fill="none" stroke="#10b981" stroke-width="8" 
                                    stroke-dasharray="{{ ($monthlyPerformance['percentage'] / 100) * 565.48 }}, 565.48"
                                    stroke-linecap="round"/>
                        @endif
                    </svg>

                    <!-- Texto no centro -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <p class="text-5xl font-bold text-slate-900 dark:text-slate-100">
                            {{ $monthlyPerformance['percentage'] }}%
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                            {{ $monthlyPerformance['classification'] }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card: Desempenho por Semana -->
            <div class="space-y-3">
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400 mb-4">Detalhamento por Semana:</p>

                @php $weeks = ['1ª', '2ª', '3ª', '4ª']; $daysOfWeek = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb']; @endphp
                @foreach ($monthlyPerformance['weeklyBreakdown'] as $index => $weekPerf)
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-600">
                        <div class="mb-3">
                            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $weeks[$index] }} semana</span>
                        </div>

                        <!-- Caixinhas dos dias da semana -->
                        <div class="flex items-center gap-x-2">
                            @foreach ($monthlyPerformance['weekDayActivities'][$index] as $dayIndex => $hasActivity)
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-md border-2 {{ $hasActivity ? 'bg-green-500 border-green-500' : 'bg-slate-200 dark:bg-slate-600 border-slate-300 dark:border-slate-500' }} transition-colors duration-200"></div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $daysOfWeek[$dayIndex] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>

    <!-- ==================== DESEMPENHO ANUAL (TABELA) ==================== -->
    <div class="bg-light-card dark:bg-slate-800 rounded-lg p-8 shadow-md border border-light-border dark:border-slate-700">
        
        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">📈 Desempenho Anual ({{ now()->year }})</h2>

        <!-- Tabela responsiva -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-light-border dark:border-slate-700">
                        <th class="px-4 py-3 text-sm font-semibold text-slate-600 dark:text-slate-400">Mês</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-600 dark:text-slate-400">Desempenho (%)</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-600 dark:text-slate-400">Barra Visual</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-600 dark:text-slate-400">Classificação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($yearlyPerformance as $monthData)
                        <tr class="border-b border-light-border dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            
                            <!-- Mês -->
                            <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">
                                {{ $monthData['month'] }}
                            </td>

                            <!-- Percentual -->
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-slate-100">
                                {{ $monthData['percentage'] }}%
                            </td>

                            <!-- Barra Visual -->
                            <td class="px-4 py-3">
                                <div class="w-32 bg-slate-300 dark:bg-slate-600 rounded-full h-2">
                                    @if ($monthData['percentage'] <= 40)
                                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $monthData['percentage'] }}%"></div>
                                    @elseif ($monthData['percentage'] <= 70)
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: {{ $monthData['percentage'] }}%"></div>
                                    @else
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $monthData['percentage'] }}%"></div>
                                    @endif
                                </div>
                            </td>

                            <!-- Classificação -->
                            <td class="px-4 py-3">
                                @if ($monthData['percentage'] <= 40)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300">
                                        Baixo
                                    </span>
                                @elseif ($monthData['percentage'] <= 70)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                                        Médio
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300">
                                        Alto
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- ==================== INFORMAÇÕES DE REALISMO ==================== -->
    <div class="bg-blue-50 dark:bg-blue-900/40 rounded-lg p-6 border border-blue-200 dark:border-blue-700">
        <p class="text-sm text-slate-700 dark:text-slate-300">
            <strong>Nota:</strong> Este sistema calcula o desempenho de forma realista. O desempenho máximo é limitado a 90%, simulando o comportamento real de produtividade humana. Dados são coletados de suas tarefas registradas no sistema.
        </p>
    </div>

</div>

@endsection
