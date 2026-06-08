@extends('layout')

@section('content')

<div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 space-y-6 py-8">

        <x-page-header
            title="Atividades do dia"
            description="Organize e acompanhe suas atividades diárias."
        />

        <!-- Card: Formulário de adição -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 hover:shadow-lg dark:hover:shadow-xl transition-all duration-300">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                Nova atividade
            </h2>

            <form method="POST" action="{{ route('task.store') }}" class="space-y-5" data-sync-dashboard="true">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input name="title" placeholder="Título da tarefa" 
                    class="bg-white dark:bg-slate-900 border-2 border-light-border dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-500 rounded-lg p-3 w-full focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition" required>

                    <input type="date" name="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                    class="bg-white dark:bg-slate-900 border-2 border-light-border dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-lg p-3 w-full focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition" required>
                </div>

                <div class="flex gap-3">
                    <select name="priority" class="bg-white dark:bg-slate-900 border-2 border-light-border dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-lg p-3 flex-1 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition">
                        <option value="baixa">Prioridade: Baixa</option>
                        <option value="media">Prioridade: Média</option>
                        <option value="alta">Prioridade: Alta</option>
                    </select>

                    <button class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-slate-100 px-8 py-3 rounded-lg font-bold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        Adicionar
                    </button>
                </div>
            </form>
        </div>

        <!-- Card: Lista de tarefas -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 overflow-hidden">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                    📌 Atividades Pendentes ({{ $tasks->count() }})
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-light-bg dark:bg-slate-900 border-b-2 border-light-border dark:border-slate-700">
                            <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Tarefa</th>
                            <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Data</th>
                            <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Prioridade</th>
                            <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Status</th>
                            <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-light-border dark:divide-slate-700">
                        @foreach($tasks as $task)
                            <tr class="hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200">
                                <td class="py-4 px-5 text-slate-900 dark:text-slate-100 font-medium">
                                    {{ $task->title }}
                                </td>

                                <td class="py-4 px-5 text-slate-600 dark:text-slate-400 text-sm">
                                    {{ \Carbon\Carbon::parse($task->date)->format('d/m/Y') }}
                                </td>

                                <td class="py-4 px-5">
                                    @if($task->priority === 'alta')
                                        <span class="px-4 py-2 bg-red-500 text-white rounded-full text-xs font-bold shadow-sm">🔴 Alta</span>
                                    @elseif($task->priority === 'media')
                                        <span class="px-4 py-2 bg-yellow-500 text-white rounded-full text-xs font-bold shadow-sm">🟡 Média</span>
                                    @else
                                        <span class="px-4 py-2 bg-green-500 text-white rounded-full text-xs font-bold shadow-sm">🟢 Baixa</span>
                                    @endif
                                </td>

                                <td class="py-4 px-5">
                                    @if($task->status)
                                        <span class="px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-xs font-bold inline-flex items-center gap-1">✓ Concluída</span>
                                    @else
                                        <span class="px-4 py-2 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-full text-xs font-bold inline-flex items-center gap-1">⏳ Pendente</span>
                                    @endif
                                </td>

                                <td class="py-4 px-5 space-x-3">
                                    <a href="{{ route('task.complete', $task->id) }}" data-sync-dashboard="true" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Concluir</a>
                                    <a href="{{ route('task.delete', $task->id) }}" data-sync-dashboard="true" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Excluir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($tasks->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400 text-lg">📭 Nenhuma atividade pendente</p>
                    <p class="text-slate-400 dark:text-slate-500 text-sm mt-2">Crie uma nova atividade para começar seu dia!</p>
                </div>
            @endif

        </div>

        <!-- Card: Tarefas Concluídas Hoje -->
        @if($completedTasks->isNotEmpty())
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 overflow-hidden">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                ✅ Tarefas Concluídas Hoje
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($completedTasks as $index => $task)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-green-600 dark:text-green-400 font-bold">{{ $index + 1 }}.</span>
                        <h3 class="font-semibold text-green-800 dark:text-green-200">{{ $task->title }}</h3>
                    </div>
                    <p class="text-sm text-green-600 dark:text-green-400">Data: {{ \Carbon\Carbon::parse($task->date)->format('d/m/Y') }}</p>
                    <div class="text-xs text-green-500 dark:text-green-500 mt-1">Concluída hoje</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-4 text-sm text-slate-700 dark:text-slate-300">
            <span class="font-semibold">ℹ️ Nota:</span> As atividades do dia ajudam no planejamento e organização da rotina. As tarefas cadastradas ficam disponíveis até serem concluídas ou removidas. Utilize as prioridades para identificar o que é mais importante e manter o foco nas atividades que exigem maior atenção.
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
        if (element.tagName === 'FORM') {
            element.addEventListener('submit', markActivitiesUpdated);
        } else {
            element.addEventListener('click', markActivitiesUpdated);
        }
    });

    window.addEventListener('beforeunload', markActivitiesUpdated);
});
</script>

@endsection