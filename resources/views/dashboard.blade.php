@extends('layout')

@section('content')

<div class="max-w-7xl mx-auto space-y-8 py-8">

    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/40 dark:to-cyan-900/40 rounded-lg p-8 shadow-md border border-blue-200 dark:border-slate-700">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <p class="text-sm text-slate-900 dark:text-blue-200" id="dataAtual"></p>
                <h1 class="text-4xl font-bold mt-2 text-slate-900 dark:text-white" id="greeting">
                    {{ Auth::check() ? 'Olá, ' . Auth::user()->name . '!' : 'Olá, Usuário!' }}
                </h1>
                <p class="text-slate-900 dark:text-blue-300 mt-2">
                    Bem-vindo de volta. Acompanhe suas tarefas e atividades do dia.
                </p>
            </div>
            <div class="text-right">
                <p class="text-5xl font-mono font-bold text-slate-900 dark:text-white" id="clock"></p>
                <p class="text-sm text-slate-900 dark:text-blue-200 mt-1">Horário atual</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6" id="dashboard-metrics-cards">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 transition duration-300 hover:scale-105 hover:shadow-xl">
            <p class="text-sm text-slate-500 dark:text-slate-400">Atividades do dia</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4" id="dashboard-total-tasks">{{ $totalTasksToday }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 transition duration-300 hover:scale-105 hover:shadow-xl">
            <p class="text-sm text-slate-500 dark:text-slate-400">Concluídas</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4" id="dashboard-completed-tasks">{{ $tasksCompletedToday }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 transition duration-300 hover:scale-105 hover:shadow-xl">
            <p class="text-sm text-slate-500 dark:text-slate-400">Pendentes</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4" id="dashboard-pending-tasks">{{ $tasksPendingToday }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 transition duration-300 hover:scale-105 hover:shadow-xl">
            <p class="text-sm text-slate-500 dark:text-slate-400">Produtividade</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4" id="dashboard-productivity">{{ $productivity }}%</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/40 dark:to-slate-800 rounded-3xl p-6 shadow-sm border border-blue-200 dark:border-slate-700 transition duration-300 hover:scale-105 hover:shadow-xl">
            <p class="text-sm text-slate-500 dark:text-slate-400">Hábitos concluídos hoje</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4">{{ $habitsCompletedToday }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-emerald-900/40 dark:to-emerald-800 rounded-3xl p-6 shadow-sm border border-green-200 dark:border-emerald-700 transition duration-300 hover:scale-105 hover:shadow-xl">
            <p class="text-sm text-slate-500 dark:text-slate-400">Metas ativas</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4">{{ $activeGoals }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Adicionados hoje</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Resumo rápido das metas, hábitos e progressos.</p>
            </div>
            <a href="{{ route('planner') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-300 hover:underline">Ir para Planner</a>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4 transition duration-300 hover:scale-105 hover:shadow-xl">
                <p class="text-sm text-slate-500 dark:text-slate-400">Metas</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-3">{{ $goalsCompletedToday }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4 transition duration-300 hover:scale-105 hover:shadow-xl">
                <p class="text-sm text-slate-500 dark:text-slate-400">Hábitos</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-3">{{ $habitsCompletedToday }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4 transition duration-300 hover:scale-105 hover:shadow-xl">
                <p class="text-sm text-slate-500 dark:text-slate-400">Leituras</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-3">{{ $readingsCompletedToday }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4 transition duration-300 hover:scale-105 hover:shadow-xl">
                <p class="text-sm text-slate-500 dark:text-slate-400">Cursos</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-3">{{ $coursesCompletedToday }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700" id="dashboard-tasks-today-section">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Atividades do dia</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Resumo compacto das atividades realizadas hoje.</p>
            </div>
            <a href="{{ route('planner') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-300 hover:underline">Abrir Planner</a>
        </div>

        @if($tasksToday->isEmpty())
            <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                <p>Sem atividades para hoje. Adicione novas atividades no Planner.</p>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" id="dashboard-tasks-today-cards">
                @foreach($tasksToday as $item)
                    @php
                        // Tarefas do dia: status booleano false = pendente, true = concluída
                        $statusLabel = $item->status ? 'Concluída' : 'Pendente';
                        $statusClass = $item->status
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200'
                            : 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-200';
                    @endphp

                    <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-5 transition duration-300 hover:scale-105 hover:shadow-xl">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-2">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $item->title }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                                    @if(isset($item->priority))
                                        • Prioridade: {{ ucfirst($item->priority) }}
                                    @endif
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script>
const dashboardUrl = '{{ route('dashboard') }}';

async function refreshDashboardData() {
    try {
        const response = await fetch(dashboardUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            return;
        }

        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const sourceTotal = doc.getElementById('dashboard-total-tasks');
        const sourceCompleted = doc.getElementById('dashboard-completed-tasks');
        const sourcePending = doc.getElementById('dashboard-pending-tasks');
        const sourceProductivity = doc.getElementById('dashboard-productivity');
        const sourceTasksSection = doc.getElementById('dashboard-tasks-today-section');

        const targetTotal = document.getElementById('dashboard-total-tasks');
        const targetCompleted = document.getElementById('dashboard-completed-tasks');
        const targetPending = document.getElementById('dashboard-pending-tasks');
        const targetProductivity = document.getElementById('dashboard-productivity');
        const targetTasksSection = document.getElementById('dashboard-tasks-today-section');

        if (sourceTotal && targetTotal) {
            targetTotal.textContent = sourceTotal.textContent.trim();
        }
        if (sourceCompleted && targetCompleted) {
            targetCompleted.textContent = sourceCompleted.textContent.trim();
        }
        if (sourcePending && targetPending) {
            targetPending.textContent = sourcePending.textContent.trim();
        }
        if (sourceProductivity && targetProductivity) {
            targetProductivity.textContent = sourceProductivity.textContent.trim();
        }
        if (sourceTasksSection && targetTasksSection) {
            targetTasksSection.innerHTML = sourceTasksSection.innerHTML;
        }
    } catch (error) {
        console.error('Falha ao atualizar o dashboard:', error);
    }
}

function updateClock() {
    const now = new Date();
    const dateElement = document.getElementById('dataAtual');
    const clockElement = document.getElementById('clock');

    if (!dateElement || !clockElement) {
        return;
    }

    dateElement.textContent = now.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' });
    clockElement.textContent = now.toLocaleTimeString('pt-BR');
}

function updateGreeting() {
    const greetingElement = document.getElementById('greeting');
    if (!greetingElement) {
        return;
    }

    const hour = new Date().getHours();
    const userName = '{{ Auth::check() ? Auth::user()->name : 'Usuário' }}';
    let greeting = `Olá, ${userName}!`;

    if (hour >= 6 && hour < 12) {
        greeting = `Bom dia, ${userName}! 🌅`;
    } else if (hour >= 12 && hour < 18) {
        greeting = `Boa tarde, ${userName}! ☀️`;
    } else {
        greeting = `Boa noite, ${userName}! 🌙`;
    }

    greetingElement.textContent = greeting;
}

window.addEventListener('load', function () {
    updateClock();
    updateGreeting();
    refreshDashboardData();
    setInterval(updateClock, 1000);
    setInterval(refreshDashboardData, 15000);

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') {
            refreshDashboardData();
        }
    });

    window.addEventListener('focus', refreshDashboardData);
    window.addEventListener('pageshow', refreshDashboardData);

    window.addEventListener('storage', function (event) {
        if (event.key === 'activitiesUpdatedAt') {
            refreshDashboardData();
        }
    });
});
</script>

@endsection
