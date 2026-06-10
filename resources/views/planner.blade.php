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
                    📌 Atividades Pendentes ({{ $tasks->total() }})
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
                                        <span class="px-4 py-2 bg-red-500 text-white rounded-full text-xs font-bold shadow-sm">Alta</span>
                                    @elseif($task->priority === 'media')
                                        <span class="px-4 py-2 bg-yellow-500 text-white rounded-full text-xs font-bold shadow-sm">Média</span>
                                    @else
                                        <span class="px-4 py-2 bg-green-500 text-white rounded-full text-xs font-bold shadow-sm">Baixa</span>
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
                                    <form id="delete-planner-task-form-{{ $task->id }}" action="{{ route('task.delete', $task->id) }}" method="GET" class="inline" data-sync-dashboard="true">
                                        <button type="button" data-task-title="{{ $task->title }}" data-form-id="delete-planner-task-form-{{ $task->id }}" class="delete-planner-task-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($tasks->hasPages())
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            @endif

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

<div id="delete-planner-task-overlay" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40"></div>
<div id="delete-planner-task-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl p-6 transform transition-all duration-300 scale-95 opacity-0" id="delete-planner-task-modal-card">
        <div class="flex items-start gap-4">
            <div class="rounded-2xl bg-red-500/10 text-red-700 dark:text-red-300 p-3">
                <span class="text-2xl">⚠️</span>
            </div>
            <div class="flex-1">
                <h3 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">⚠️ Excluir Atividade</h3>
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-6">
                    Tem certeza que deseja excluir a atividade:
                </p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-slate-100" id="delete-planner-task-title">"Título da atividade"</p>
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-6">
                    Esta ação removerá permanentemente a atividade selecionada e não poderá ser desfeita.
                </p>
            </div>
        </div>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button id="cancel-delete-planner-task" type="button" class="rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                ❌ Cancelar
            </button>
            <button id="confirm-delete-planner-task" type="button" class="rounded-2xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">
                🗑️ Excluir Atividade
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-planner-task-button');
        const modal = document.getElementById('delete-planner-task-modal');
        const overlay = document.getElementById('delete-planner-task-overlay');
        const titleNode = document.getElementById('delete-planner-task-title');
        const confirmButton = document.getElementById('confirm-delete-planner-task');
        const cancelButton = document.getElementById('cancel-delete-planner-task');
        const modalCard = document.getElementById('delete-planner-task-modal-card');
        let currentForm = null;

        function openModal(taskTitle, formId) {
            currentForm = document.getElementById(formId);
            titleNode.textContent = '"' + taskTitle + '"';
            modal.classList.remove('hidden');
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                modalCard.classList.remove('scale-95', 'opacity-0');
            });
        }

        function closeModal() {
            modalCard.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                overlay.classList.add('hidden');
            }, 200);
            currentForm = null;
        }

        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const taskTitle = this.dataset.taskTitle;
                const formId = this.dataset.formId;
                openModal(taskTitle, formId);
            });
        });

        confirmButton.addEventListener('click', function () {
            if (currentForm) {
                currentForm.submit();
            }
        });

        cancelButton.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);
    });

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