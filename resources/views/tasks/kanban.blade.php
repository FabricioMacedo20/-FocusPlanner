@extends('layout')

@section('content')

<div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- HEADER: Título e data de hoje -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-3">
                📋 Quadro Kanban
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                {{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>

        <!-- MENSAGENS DE SUCESSO/ERRO -->
        @if ($message = Session::get('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 rounded-lg text-green-800 dark:text-green-200">
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 rounded-lg text-red-800 dark:text-red-200">
                {{ $message }}
            </div>
        @endif

        <!-- BOTÃO PARA CRIAR NOVA TAREFA -->
        <div class="mb-8">
            <a href="{{ route('planner') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                ➕ Nova Tarefa
            </a>
        </div>

        <!-- QUADRO KANBAN: 3 COLUNAS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- COLUNA 1: A FAZER (status = 0) -->
            <div class="bg-light-card dark:bg-slate-800 rounded-lg p-6 shadow-md dark:shadow-lg border-l-4 border-slate-400 dark:border-slate-600">
                
                <!-- CABEÇALHO DA COLUNA -->
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        📌 A Fazer
                    </h2>
                    <!-- CONTADOR DE TAREFAS -->
                    <span class="bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-full px-3 py-1 text-sm font-semibold">
                        {{ $tasks->where('status', 0)->count() }}
                    </span>
                </div>

                <!-- LISTA DE TAREFAS A FAZER -->
                <div class="space-y-3">
                    @forelse($tasks->where('status', 0) as $task)
                        <!-- CARD DA TAREFA -->
                        <div class="bg-white dark:bg-slate-700 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-slate-400 dark:border-slate-600">
                            
                            <!-- TÍTULO DA TAREFA -->
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                {{ $task->title }}
                            </h3>

                            <!-- DESCRIÇÃO DA TAREFA -->
                            @if($task->description)
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                                    {{ $task->description }}
                                </p>
                            @endif

                            <!-- PRIORIDADE E DATA -->
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
                                <!-- PRIORIDADE -->
                                <span class="px-2 py-1 rounded-full @if($task->priority == 'alta') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 @elseif($task->priority == 'media') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 @else bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 @endif font-semibold">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                <!-- HORÁRIO DE CRIAÇÃO -->
                                <span>{{ $task->created_at->format('H:i') }}</span>
                            </div>

                            <!-- BOTÕES DE AÇÃO -->
                            <div class="flex gap-2">
                                <!-- BOTÃO INICIAR: Move para Em Andamento (status = 2) -->
                                <form action="{{ route('task.update-status', [$task->id, 2]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 rounded-lg font-semibold transition-colors duration-300">
                                        ▶️ Iniciar
                                    </button>
                                </form>

                                <!-- BOTÃO DELETAR -->
                                <form action="{{ route('task.delete', $task->id) }}" method="GET" class="flex-1" onsubmit="return confirm('Tem certeza que deseja deletar esta tarefa?')">
                                    <button type="submit" class="w-full text-center bg-red-500 hover:bg-red-600 text-white text-sm py-2 rounded-lg font-semibold transition-colors duration-300">
                                        🗑️ Deletar
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <!-- MENSAGEM QUANDO NENHUMA TAREFA -->
                        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                            <p class="text-sm">Nenhuma tarefa para fazer</p>
                        </div>
                    @endforelse
                </div>

            </div>

            <!-- COLUNA 2: EM ANDAMENTO (status = 2) -->
            <div class="bg-light-card dark:bg-slate-800 rounded-lg p-6 shadow-md dark:shadow-lg border-l-4 border-blue-400 dark:border-blue-600">
                
                <!-- CABEÇALHO DA COLUNA -->
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        ⚙️ Em Andamento
                    </h2>
                    <!-- CONTADOR DE TAREFAS -->
                    <span class="bg-blue-200 dark:bg-blue-900 text-blue-900 dark:text-blue-200 rounded-full px-3 py-1 text-sm font-semibold">
                        {{ $tasks->where('status', 2)->count() }}
                    </span>
                </div>

                <!-- LISTA DE TAREFAS EM ANDAMENTO -->
                <div class="space-y-3">
                    @forelse($tasks->where('status', 2) as $task)
                        <!-- CARD DA TAREFA -->
                        <div class="bg-blue-50 dark:bg-slate-700 rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-blue-400 dark:border-blue-600">
                            
                            <!-- TÍTULO DA TAREFA -->
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                {{ $task->title }}
                            </h3>

                            <!-- DESCRIÇÃO DA TAREFA -->
                            @if($task->description)
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                                    {{ $task->description }}
                                </p>
                            @endif

                            <!-- PRIORIDADE E DATA -->
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
                                <!-- PRIORIDADE -->
                                <span class="px-2 py-1 rounded-full @if($task->priority == 'alta') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 @elseif($task->priority == 'media') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 @else bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 @endif font-semibold">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                <!-- HORÁRIO DE CRIAÇÃO -->
                                <span>{{ $task->created_at->format('H:i') }}</span>
                            </div>

                            <!-- BOTÕES DE AÇÃO -->
                            <div class="flex gap-2">
                                <!-- BOTÃO CONCLUIR: Move para Concluído (status = 1) -->
                                <form action="{{ route('task.update-status', [$task->id, 1]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-green-500 hover:bg-green-600 text-white text-sm py-2 rounded-lg font-semibold transition-colors duration-300">
                                        ✅ Concluir
                                    </button>
                                </form>

                                <!-- BOTÃO VOLTAR: Move de volta para A Fazer (status = 0) -->
                                <form action="{{ route('task.update-status', [$task->id, 0]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-slate-500 hover:bg-slate-600 text-white text-sm py-2 rounded-lg font-semibold transition-colors duration-300">
                                        ↩️ Voltar
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <!-- MENSAGEM QUANDO NENHUMA TAREFA -->
                        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                            <p class="text-sm">Nenhuma tarefa em andamento</p>
                        </div>
                    @endforelse
                </div>

            </div>

            <!-- COLUNA 3: CONCLUÍDO (status = 1) -->
            <div class="bg-light-card dark:bg-slate-800 rounded-lg p-6 shadow-md dark:shadow-lg border-l-4 border-green-400 dark:border-green-600">
                
                <!-- CABEÇALHO DA COLUNA -->
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        ✅ Concluído
                    </h2>
                    <!-- CONTADOR DE TAREFAS -->
                    <span class="bg-green-200 dark:bg-green-900 text-green-900 dark:text-green-200 rounded-full px-3 py-1 text-sm font-semibold">
                        {{ $tasks->where('status', 1)->count() }}
                    </span>
                </div>

                <!-- LISTA DE TAREFAS CONCLUÍDAS -->
                <div class="space-y-3">
                    @forelse($tasks->where('status', 1) as $task)
                        <!-- CARD DA TAREFA -->
                        <div class="bg-green-50 dark:bg-slate-700 rounded-lg p-4 shadow-md border-l-4 border-green-400 dark:border-green-600 opacity-75">
                            
                            <!-- TÍTULO DA TAREFA (COM STRIKETHROUGH) -->
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2 line-through">
                                {{ $task->title }}
                            </h3>

                            <!-- DESCRIÇÃO DA TAREFA -->
                            @if($task->description)
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3 line-through">
                                    {{ $task->description }}
                                </p>
                            @endif

                            <!-- PRIORIDADE E DATA -->
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
                                <!-- PRIORIDADE -->
                                <span class="px-2 py-1 rounded-full @if($task->priority == 'alta') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 @elseif($task->priority == 'media') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 @else bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 @endif font-semibold">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                <!-- HORÁRIO DE CONCLUSÃO -->
                                <span>{{ $task->updated_at->format('H:i') }}</span>
                            </div>

                            <!-- BOTÕES DE AÇÃO -->
                            <div class="flex gap-2">
                                <!-- BOTÃO REABRIR: Move de volta para A Fazer (status = 0) -->
                                <form action="{{ route('task.update-status', [$task->id, 0]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 rounded-lg font-semibold transition-colors duration-300">
                                        🔄 Reabrir
                                    </button>
                                </form>

                                <!-- BOTÃO DELETAR -->
                                <form action="{{ route('task.delete', $task->id) }}" method="GET" class="flex-1" onsubmit="return confirm('Tem certeza que deseja deletar esta tarefa?')">
                                    <button type="submit" class="w-full text-center bg-red-500 hover:bg-red-600 text-white text-sm py-2 rounded-lg font-semibold transition-colors duration-300">
                                        🗑️ Deletar
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <!-- MENSAGEM QUANDO NENHUMA TAREFA -->
                        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                            <p class="text-sm">Nenhuma tarefa concluída</p>
                        </div>
                    @endforelse
                </div>

            </div>

        </div>

        <!-- ESTATÍSTICAS GERAIS -->
        <div class="mt-8 bg-light-card dark:bg-slate-800 rounded-lg p-6 border border-light-border dark:border-slate-700">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">📊 Resumo do Dia</h3>
            
            <div class="grid grid-cols-3 gap-4">
                <!-- TOTAL A FAZER -->
                <div class="text-center">
                    <p class="text-3xl font-bold text-slate-400 dark:text-slate-500">{{ $tasks->where('status', 0)->count() }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">A Fazer</p>
                </div>

                <!-- TOTAL EM ANDAMENTO -->
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-500">{{ $tasks->where('status', 2)->count() }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Em Andamento</p>
                </div>

                <!-- TOTAL CONCLUÍDO -->
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-500">{{ $tasks->where('status', 1)->count() }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Concluído</p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
