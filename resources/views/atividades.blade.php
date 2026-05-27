@extends('layout')

@section('content')

<div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100">📌 Atividades do Dia</h1>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">
                    {{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </p>
            </div>
            <a href="{{ route('planner') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                ➕ Nova Tarefa
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 rounded-lg text-green-800 dark:text-green-200">
                ✅ {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 rounded-lg text-red-800 dark:text-red-200">
                ❌ {{ $message }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Lista de Atividades</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Todas as tarefas do dia com ações simples.</p>
                </div>
                <div class="flex flex-wrap gap-3 text-sm text-slate-600 dark:text-slate-400">
                    <span class="px-3 py-2 bg-slate-100 dark:bg-slate-800 rounded-full">A Fazer: {{ $tasks->where('status', 0)->count() }}</span>
                    <span class="px-3 py-2 bg-blue-100 dark:bg-blue-900/30 rounded-full">Em andamento: {{ $tasks->where('status', 2)->count() }}</span>
                    <span class="px-3 py-2 bg-green-100 dark:bg-green-900/30 rounded-full">Concluídas: {{ $tasks->where('status', 1)->count() }}</span>
                </div>
            </div>

            @if($tasks->isEmpty())
                <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                    <p class="text-lg">Nenhuma tarefa encontrada para hoje.</p>
                    <p class="mt-2">Adicione uma nova tarefa no Planner para começar.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800">
                                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200">Tarefa</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200">Data</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200">Prioridade</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200">Status</th>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                                    <td class="px-4 py-4 text-slate-900 dark:text-slate-100 font-medium">{{ $task->title }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($task->date)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $task->priority == 'alta' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : ($task->priority == 'media' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        @if($task->status === 0)
                                            <span class="text-orange-600 dark:text-orange-300">Pendente</span>
                                        @elseif($task->status === 2)
                                            <span class="text-blue-600 dark:text-blue-300">Em andamento</span>
                                        @else
                                            <span class="text-green-600 dark:text-green-300">Concluída</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 space-x-2">
                                        @if($task->status !== 1)
                                            <a href="{{ route('task.complete', $task->id) }}" data-sync-dashboard="true" style="display: inline-flex; align-items: center; padding: 8px 16px; background-color: #10b981; color: white; border-radius: 8px; font-weight: bold; text-decoration: none; border: 1px solid #e2e8f0; cursor: pointer;" onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10b981'">Concluir</a>
                                        @endif
                                        <form action="{{ route('task.delete', $task->id) }}" method="GET" class="inline" data-sync-dashboard="true" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                            <button type="submit" style="display: inline-flex; align-items: center; padding: 8px 16px; background-color: #dc2626; color: white; border-radius: 8px; font-weight: bold; border: 1px solid #e2e8f0; cursor: pointer;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function markActivitiesUpdated() {
    try {
        localStorage.setItem('activitiesUpdatedAt', Date.now().toString());
    } catch (error) {
        console.warn('Não foi possível atualizar o evento de atividades:', error);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-sync-dashboard]').forEach(function (element) {
        element.addEventListener('click', markActivitiesUpdated);
    });
    document.querySelectorAll('form[data-sync-dashboard]').forEach(function (form) {
        form.addEventListener('submit', markActivitiesUpdated);
    });

    window.addEventListener('beforeunload', markActivitiesUpdated);
});
</script>

@endsection
