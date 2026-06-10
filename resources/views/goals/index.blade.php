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

        <!-- RESUMO DAS METAS -->
        @if($totalActiveGoals > 0 || $totalCompletedGoals > 0)
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-2xl p-6 shadow-md dark:shadow-lg border border-blue-200 dark:border-blue-700/50">
            <h2 class="text-lg font-bold text-blue-900 dark:text-blue-100 mb-4 flex items-center gap-2">
                📊 Resumo das Metas
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-blue-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Metas Ativas</p>
                    <p class="text-3xl font-bold text-white dark:text-white mt-1">{{ $totalActiveGoals }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-green-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Metas Concluídas</p>
                    <p class="text-3xl font-bold text-white dark:text-white mt-1">{{ $totalCompletedGoals }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-purple-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Taxa de Conclusão</p>
                    <p class="text-3xl font-bold text-white dark:text-white mt-1">{{ $completionRate }}%</p>
                </div>
            </div>
        </div>

        <!-- MENSAGEM DE CONTEXTO -->
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800 flex items-start gap-3">
            <span class="text-2xl">🎯</span>
            <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $contextMessage }}</p>
        </div>

        <!-- META EM DESTAQUE -->
        @if($featuredGoal)
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-2xl p-6 shadow-md dark:shadow-lg border-2 border-blue-300 dark:border-blue-700/50">
            <h2 class="text-lg font-bold text-blue-900 dark:text-blue-100 mb-4 flex items-center gap-2">
                ⭐ Meta Principal
            </h2>
            <h3 class="text-2xl font-bold text-blue-950 dark:text-blue-50 mb-6">{{ $featuredGoal->title }}</h3>
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                        @if($featuredGoal->status)
                            ✅ Concluída
                        @else
                            ⏳ Em andamento
                        @endif
                    </span>
                </div>
                <div class="text-sm text-blue-700 dark:text-blue-300">
                    📅 Criada em {{ \Carbon\Carbon::parse($featuredGoal->created_at)->format('d/m/Y') }}
                </div>
                @if($featuredGoal->status)
                    <div class="text-sm text-blue-700 dark:text-blue-300">
                        ✨ Concluída em {{ \Carbon\Carbon::parse($featuredGoal->updated_at)->format('d/m/Y') }}
                    </div>
                @endif
            </div>
        </div>
        @endif
        @endif

        <!-- Card principal - TABELA DE METAS ATIVAS -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 overflow-hidden">
            @if($goals->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400 text-lg">Nenhuma meta ativa no momento</p>
                    <p class="text-slate-400 dark:text-slate-500 text-sm mt-2">Crie uma nova meta para acompanhar seu progresso!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-light-bg dark:bg-slate-900 border-b-2 border-light-border dark:border-slate-700">
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Meta</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Criada em</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Status</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-light-border dark:divide-slate-700">
                            @foreach($goals as $goal)
                                <tr class="hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200">
                                    <td class="py-4 px-5 text-slate-900 dark:text-slate-100 font-medium">
                                        {{ $goal->title }}
                                    </td>
                                    <td class="py-4 px-5 text-slate-600 dark:text-slate-400 text-sm">
                                        {{ \Carbon\Carbon::parse($goal->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                            ⏳ Em andamento
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 space-x-3 text-sm">
                                        <a href="{{ route('goals.edit', $goal) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-white dark:border-slate-700">Editar</a>
                                        <a href="{{ route('goals.set-featured', $goal) }}" class="inline-flex items-center px-4 py-2 {{ $goal->is_featured ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-gray-600 hover:bg-gray-700' }} dark:{{ $goal->is_featured ? 'bg-yellow-600 dark:hover:bg-yellow-700' : 'bg-gray-700 dark:hover:bg-gray-600' }} text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                                            {{ $goal->is_featured ? '⭐ Principal' : '☆ Tornar Principal' }}
                                        </a>
                                        <a href="{{ route('goals.toggle-status', $goal) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                                            Concluir
                                        </a>
                                        <form id="delete-goal-form-{{ $goal->id }}" class="inline" method="POST" action="{{ route('goals.destroy', $goal) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-goal-title="{{ $goal->title }}" data-form-id="delete-goal-form-{{ $goal->id }}" class="delete-goal-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($goals->hasPages())
                    <div class="mt-6">
                        {{ $goals->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Seção Concluídas -->
        @if($completedGoals->isNotEmpty())
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                ✅ Metas Concluídas ({{ $completedGoals->count() }})
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($completedGoals->take(5) as $goal)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800 dark:text-green-200 mb-3">✅ {{ $goal->title }}</h3>
                    <div class="text-sm text-green-600 dark:text-green-400">
                        📅 Concluída em {{ \Carbon\Carbon::parse($goal->updated_at)->format('d/m/Y') }}
                    </div>
                </div>
                @endforeach
            </div>

            @if($completedGoals->count() > 5)
            <details class="mt-6 border-t border-slate-200 dark:border-slate-700 pt-6">
                <summary class="cursor-pointer font-bold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 transition-colors">
                    📁 Ver todas as {{ $completedGoals->count() }} metas concluídas
                </summary>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    @foreach($completedGoals->skip(5) as $goal)
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <h3 class="font-semibold text-green-800 dark:text-green-200 mb-3">✅ {{ $goal->title }}</h3>
                        <div class="text-sm text-green-600 dark:text-green-400">
                            📅 Concluída em {{ \Carbon\Carbon::parse($goal->updated_at)->format('d/m/Y') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </details>
            @endif

            <div class="mt-6 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-4 text-sm text-slate-700 dark:text-slate-300">
                <strong>ℹ️ Nota:</strong> Você concluiu <strong>{{ $completedGoals->count() }}</strong> meta(s) até o momento. Parabéns pelos objetivos alcançados! Continue definindo novas metas e acompanhando seu progresso pessoal.
            </div>
        </div>
        @endif

    </div>

    <div id="delete-goal-overlay" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40"></div>
    <div id="delete-goal-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl p-6 transform transition-all duration-300 scale-95 opacity-0" id="delete-goal-modal-card">
            <div class="flex items-start gap-4">
                <div class="rounded-2xl bg-red-500/10 text-red-700 dark:text-red-300 p-3">
                    <span class="text-2xl">⚠️</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">⚠️ Excluir Meta</h3>
                    <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-6">
                        Tem certeza que deseja excluir a meta:
                    </p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-slate-100" id="delete-goal-title">"Título da meta"</p>
                    <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-6">
                        Esta ação removerá permanentemente a meta selecionada e não poderá ser desfeita.
                    </p>
                </div>
            </div>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button id="cancel-delete-goal" type="button" class="rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    ❌ Cancelar
                </button>
                <button id="confirm-delete-goal" type="button" class="rounded-2xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">
                    🗑️ Excluir Meta
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-goal-button');
            const modal = document.getElementById('delete-goal-modal');
            const overlay = document.getElementById('delete-goal-overlay');
            const titleNode = document.getElementById('delete-goal-title');
            const confirmButton = document.getElementById('confirm-delete-goal');
            const cancelButton = document.getElementById('cancel-delete-goal');
            const modalCard = document.getElementById('delete-goal-modal-card');
            let currentForm = null;

            function openModal(goalTitle, formId) {
                currentForm = document.getElementById(formId);
                titleNode.textContent = '"' + goalTitle + '"';
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
                    const goalTitle = this.dataset.goalTitle;
                    const formId = this.dataset.formId;
                    openModal(goalTitle, formId);
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
    </script>
</div>

@endsection
