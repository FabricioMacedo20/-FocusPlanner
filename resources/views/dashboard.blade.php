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
                    Bem-vindo de volta. Acompanhe suas tarefas do dia.
                </p>
            </div>
            <div class="text-right">
                <p class="text-5xl font-mono font-bold text-slate-900 dark:text-white" id="clock"></p>
                <p class="text-sm text-slate-900 dark:text-blue-200 mt-1">Horário atual</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Tarefas do dia</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4">{{ $total }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Concluídas</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4">{{ $completed }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Pendentes</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4">{{ $pending }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Produtividade</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4">{{ $rate }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/40 dark:to-slate-800 rounded-3xl p-6 shadow-sm border border-blue-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">Hábitos concluídos hoje</p>
            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-4">{{ $habitsCompletedToday }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-emerald-900/40 dark:to-emerald-800 rounded-3xl p-6 shadow-sm border border-green-200 dark:border-emerald-700">
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
            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                <p class="text-sm text-slate-500 dark:text-slate-400">Metas</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-3">{{ $goalsCompletedToday }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                <p class="text-sm text-slate-500 dark:text-slate-400">Hábitos</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-3">{{ $habitsCompletedToday }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                <p class="text-sm text-slate-500 dark:text-slate-400">Leituras</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-3">{{ $readingsCompletedToday }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                <p class="text-sm text-slate-500 dark:text-slate-400">Cursos</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-3">{{ $coursesCompletedToday }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Tarefas de hoje</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Lista de tarefas criadas para hoje.</p>
            </div>
            <a href="{{ route('planner') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-300 hover:underline">Abrir Planner</a>
        </div>

        @if($tasksToday->isEmpty())
            <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                <p>Sem tarefas para hoje. Adicione novas tarefas no Planner.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($tasksToday as $task)
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $task->title }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($task->date)->format('d/m/Y') }} • Prioridade: {{ ucfirst($task->priority) }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-2 text-xs font-semibold {{ $task->status ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-200' }}">
                            {{ $task->status ? 'Concluída' : 'Pendente' }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script>
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
    setInterval(updateClock, 1000);
});
</script>

@endsection
