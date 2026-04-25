@extends('layout')

@section('content')

<div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 py-8">

        <!-- Header com título e botão voltar -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-3">
                📚 {{ $course->name }}
            </h1>
            <a href="{{ route('courses.index') }}" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-slate-100 px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                ← Voltar
            </a>
        </div>

        <!-- Card principal -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 overflow-hidden">
            <!-- Progresso -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-slate-900 dark:text-slate-100 font-semibold">Progresso</span>
                    <span class="text-slate-600 dark:text-slate-400">{{ $course->progress }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 h-3 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 h-3 rounded-full transition-all duration-500" style="width: {{ min($course->progress, 100) }}%"></div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4">Conteúdo do Curso</h2>
                @if($course->content)
                    <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                        <div class="prose dark:prose-invert max-w-none">
                            {!! nl2br(e($course->content)) !!}
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                        <p>Nenhum conteúdo adicionado ainda.</p>
                        <p class="text-sm mt-2">Edite o curso para adicionar anotações e tópicos.</p>
                    </div>
                @endif
            </div>

            <!-- Ações -->
            <div class="flex gap-3">
                <a href="{{ route('courses.edit', $course) }}" class="bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Editar Curso</a>
            </div>
        </div>

    </div>
</div>

@endsection