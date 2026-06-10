@extends('layout')

@section('content')

<div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-3">
                📝 Atividades do Dia
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                {{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>

        <!-- Botão para adicionar nova atividade -->
        <div class="mb-6">
            <button onclick="document.getElementById('new-note-modal').classList.remove('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                ➕ Nova Atividade
            </button>
        </div>

        <!-- Lista de atividades -->
        <div class="space-y-4">
            @forelse($notes as $note)
            <div class="bg-light-card dark:bg-slate-800 rounded-lg p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">{{ $note->title }}</h3>
                        @if($note->content)
                            <p class="text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">{{ $note->content }}</p>
                        @endif
                        <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                            <span>📅 {{ $note->created_at->format('H:i') }}</span>
                            @if($note->important)
                                <span class="text-red-500">⭐ Importante</span>
                            @endif
                            @if($note->completed)
                                <span class="text-green-500">✅ Concluída</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        @if(!$note->completed)
                            <form method="POST" action="{{ route('notes.complete', $note) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-500 hover:text-green-700 text-sm font-medium">
                                    ✅ Concluir
                                </button>
                            </form>
                        @endif
                        <button onclick="editNote({{ $note->id }}, '{{ addslashes($note->title) }}', '{{ addslashes($note->content) }}')"
                                class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                            ✏️ Editar
                        </button>
                        <form method="POST" action="{{ route('notes.destroy', $note) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta atividade?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                🗑️ Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">Nenhuma atividade hoje</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-6">Comece adicionando sua primeira atividade do dia!</p>
                <button onclick="document.getElementById('new-note-modal').classList.remove('hidden')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    ➕ Adicionar Atividade
                </button>
            </div>
            @endforelse
        </div>

    </div>
</div>

<!-- Modal para nova atividade -->
<div id="new-note-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Nova Atividade</h3>
        <form method="POST" action="{{ route('notes.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Título</label>
                <input type="text" name="title" required
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descrição</label>
                <textarea name="content" rows="3"
                          class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('new-note-modal').classList.add('hidden')"
                        class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-transparent dark:text-slate-400 dark:hover:text-slate-200 dark:border-slate-700">
                    Cancelar
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-blue-600 dark:text-white dark:hover:bg-blue-700">
                    Criar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar atividade -->
<div id="edit-note-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Editar Atividade</h3>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Título</label>
                <input type="text" id="edit-title" name="title" required
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descrição</label>
                <textarea id="edit-content" name="content" rows="3"
                          class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('edit-note-modal').classList.add('hidden')"
                        class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-transparent dark:text-slate-400 dark:hover:text-slate-200 dark:border-slate-700">
                    Cancelar
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-blue-600 dark:text-white dark:hover:bg-blue-700">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editNote(id, title, content) {
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-content').value = content;
    document.getElementById('edit-form').action = `/notes/${id}`;
    document.getElementById('edit-note-modal').classList.remove('hidden');
}
</script>

@endsection