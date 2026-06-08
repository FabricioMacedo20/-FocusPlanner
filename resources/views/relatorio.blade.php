@extends('layout')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    <!-- Cabeçalho com título e descrição -->
    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/40 dark:to-cyan-900/40 rounded-lg p-8 shadow-md border border-blue-200 dark:border-slate-700">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100">Relatório de Desempenho</h1>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/40 dark:to-slate-800 rounded-lg p-6 border border-green-200 dark:border-green-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Tarefas Concluídas</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['completedTasks'] }}/{{ $weeklyPerformance['totalTasks'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/40 dark:to-slate-800 rounded-lg p-6 border border-green-200 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Hábitos Completados</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['completedHabits'] }}/{{ $weeklyPerformance['totalHabits'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/40 dark:to-slate-800 rounded-lg p-6 border border-green-200 dark:border-green-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Metas concluídas</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['completedGoals'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/40 dark:to-slate-800 rounded-lg p-6 border border-green-200 dark:border-green-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Metas ativas</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['activeGoals'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/40 dark:to-slate-800 rounded-lg p-6 border border-green-200 dark:border-green-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Cursos atualizados</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['coursesUpdated'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/40 dark:to-slate-800 rounded-lg p-6 border border-green-200 dark:border-green-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Leituras atualizadas</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $weeklyPerformance['readingsUpdated'] }}</p>
                </div>
            </div>

        </div>

    </div>

    <div class="bg-blue-50 dark:bg-blue-900/40 rounded-lg p-6 border border-blue-200 dark:border-blue-700">
        <p class="text-sm text-slate-700 dark:text-slate-300">
            Nota: Este relatório considera sempre o período de segunda-feira a domingo. O desempenho semanal é calculado com base nas atividades realizadas e registradas durante essa semana, apresentando uma visão geral da sua produtividade.
        </p>
    </div>

    <!-- ==================== DESEMPENHO MENSAL ==================== -->
    <div class="bg-light-card dark:bg-slate-800 rounded-lg p-8 shadow-md border border-light-border dark:border-slate-700">
        
        @php
            $monthNames = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
        @endphp

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">📆 Desempenho do Mês ({{ $monthlyPerformance['month'] }})</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Escolha o mês e marque os dias em que foi produtivo.</p>
            </div>
            <form method="GET" action="{{ route('relatorio') }}" class="flex items-center gap-3">
                <label for="month" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Mês:</label>
                <select id="month" name="month" onchange="this.form.submit()" class="rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (range(1, 12) as $monthNumber)
                        <option value="{{ $monthNumber }}" {{ $monthlyPerformance['selectedMonth'] === $monthNumber ? 'selected' : '' }}>
                            {{ ucfirst($monthNames[$monthNumber - 1]) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6">

            <div class="space-y-4">
                <div class="relative w-full h-72 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-6 flex flex-col items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full transform -rotate-90" viewBox="0 0 200 200">
                        <circle cx="100" cy="100" r="90" fill="none" stroke="#e5e7eb" stroke-width="8" class="dark:stroke-slate-700"/>
                        <circle cx="100" cy="100" r="90" fill="none" stroke="{{ $monthlyPerformance['color'] === 'red' ? '#ef4444' : ($monthlyPerformance['color'] === 'yellow' ? '#f59e0b' : '#10b981') }}" stroke-width="8"
                                stroke-dasharray="{{ ($monthlyPerformance['percentage'] / 100) * 565.48 }}, 565.48"
                                stroke-linecap="round"/>
                    </svg>

                    <div class="relative text-center">
                        <p id="monthly-percentage" class="text-5xl font-bold text-slate-900 dark:text-slate-100">{{ $monthlyPerformance['percentage'] }}%</p>
                        <p id="monthly-classification" class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ $monthlyPerformance['classification'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-5">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Dias marcados</p>
                        <p id="monthly-marked-days" class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ count($monthlyPerformance['markedDays']) }}/{{ $monthlyPerformance['totalDays'] }}</p>
                    </div>
                </div>
            </div>

            <div>
                <div class="rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-5">
                    <p class="text-sm font-semibold text-slate-600 dark:text-slate-300 mb-4">Clique nos dias produtivos</p>
                    <div class="grid grid-cols-7 gap-2">
                        @foreach ($monthlyPerformance['days'] as $day)
                            <button type="button"
                                data-day="{{ $day['day'] }}"
                                class="day-toggle group flex flex-col items-center justify-center rounded-2xl border px-2 py-3 text-center text-sm font-semibold transition-all duration-200 focus:outline-none {{ $day['marked'] ? 'bg-green-500 text-white border-green-500' : 'bg-slate-100 dark:bg-slate-700 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-slate-100 hover:bg-slate-200 dark:hover:bg-slate-600' }}"
                                title="Clique para marcar o dia {{ $day['day'] }} como produtivo">
                                <span class="text-base leading-none">{{ $day['day'] }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">{{ $day['weekday'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const token = '{{ csrf_token() }}';
            const month = {{ $monthlyPerformance['selectedMonth'] }};
            const year = {{ $monthlyPerformance['selectedYear'] }};

            document.querySelectorAll('.day-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    const day = parseInt(this.dataset.day, 10);
                    const isMarked = !this.classList.contains('bg-green-500');

                    this.classList.toggle('bg-green-500', isMarked);
                    this.classList.toggle('text-white', isMarked);
                    this.classList.toggle('border-green-500', isMarked);
                    this.classList.toggle('bg-slate-100', !isMarked);
                    this.classList.toggle('dark:bg-slate-700', !isMarked);
                    this.classList.toggle('text-slate-900', !isMarked);
                    this.classList.toggle('dark:text-slate-100', !isMarked);

                    fetch('{{ route('relatorio.save-day') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            month: month,
                            year: year,
                            day: day,
                            marked: isMarked,
                        }),
                    })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Falha ao salvar');
                        }
                        return response.json();
                    })
                    .then(function (data) {
                        document.getElementById('monthly-percentage').textContent = data.percentage + '%';
                        document.getElementById('monthly-classification').textContent = data.classification;
                        document.getElementById('monthly-marked-days').textContent = data.markedDays + '/' + data.totalDays;
                    })
                    .catch(function () {
                        window.location.reload();
                    });
                });
            });
        });
    </script>

    <!-- ==================== INFORMAÇÕES DE REALISMO ==================== -->
    <div class="bg-blue-50 dark:bg-blue-900/40 rounded-lg p-6 border border-blue-200 dark:border-blue-700">
        <p class="text-sm text-slate-700 dark:text-slate-300">
            <strong>Nota:</strong> O percentual exibido é calculado automaticamente a partir dos dias marcados pelo usuário no mês selecionado. Quanto maior a frequência de registros produtivos, maior será o desempenho apresentado no relatório.
        </p>
    </div>

</div>

@endsection
