@extends('layout')

@section('content')

    <div class="min-h-screen bg-light-bg dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 space-y-6 py-8">

        <x-page-header
            title="Cursos"
            description="Acompanhe seus cursos e seu desenvolvimento contínuo."
        >
            <a href="{{ route('courses.create') }}" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-slate-100 px-6 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                Novo curso
            </a>
        </x-page-header>

        <!-- RESUMO DOS CURSOS -->
        <div class="bg-gradient-to-r from-sky-50 to-cyan-50 dark:from-sky-900/20 dark:to-cyan-900/20 rounded-2xl p-6 shadow-md dark:shadow-lg border border-sky-200 dark:border-sky-700/50">
            <h2 class="text-lg font-bold text-sky-900 dark:text-sky-100 mb-4 flex items-center gap-2">
                📊 Resumo dos Cursos
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-sky-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Cursos cadastrados</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $totalCourses }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-amber-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Cursos em andamento</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $coursesInProgress }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-violet-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Cursos não iniciados</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $coursesNotStarted }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-emerald-100 dark:border-slate-700">
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Progresso médio</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $averageProgress }}%</p>
                </div>
            </div>
        </div>

        <!-- CURSO EM DESTAQUE -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 mt-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                📚 Curso em Destaque
            </h2>
            @if($totalCourses === 0)
                <p class="text-slate-600 dark:text-slate-400">Nenhum curso cadastrado.</p>
            @elseif(!$featuredCourse)
                <p class="text-slate-600 dark:text-slate-400">Todos os cursos cadastrados foram concluídos.</p>
            @else
                <div class="space-y-3">
                    <div class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $featuredCourse->name }}</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">{{ $featuredCourse->progress }}% concluído</div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 h-3 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 h-3 rounded-full transition-all duration-500" style="width: {{ min($featuredCourse->progress, 100) }}%"></div>
                    </div>
                </div>
            @endif
        </div>

        <!-- MENSAGEM CONTEXTUAL -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800 flex items-start gap-3 mt-6">
            <span class="text-2xl">💬</span>
            <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $contextMessage }}</p>
        </div>

        <!-- Card principal -->
        <div class="bg-light-card dark:bg-slate-800 rounded-2xl p-6 shadow-md dark:shadow-lg border border-light-border dark:border-slate-700 overflow-hidden">
            @if($courses->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400 text-lg">Nenhum curso registrado</p>
                    <p class="text-slate-400 dark:text-slate-500 text-sm mt-2">Adicione um curso para acompanhar seu progresso!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-light-bg dark:bg-slate-900 border-b-2 border-light-border dark:border-slate-700">
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Curso</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Progresso</th>
                                <th class="text-left py-4 px-5 text-slate-900 dark:text-slate-100 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-light-border dark:divide-slate-700">
                            @foreach($courses as $course)
                                <tr class="hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200">
                                    <td class="py-4 px-5 text-slate-900 dark:text-slate-100 font-medium">
                                        {{ $course->name }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-2">{{ $course->progress }}%</div>
                                        <div class="w-full bg-slate-200 dark:bg-slate-700 h-3 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700 h-3 rounded-full transition-all duration-500" style="width: {{ min($course->progress, 100) }}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5 space-x-3 text-sm">
                                        <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-green-600 dark:hover:bg-green-700 dark:text-white dark:border-slate-700">Acessar Matéria</a>
                                        <a href="{{ route('courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-900 hover:bg-slate-200 transition-all duration-200 shadow-sm dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-white dark:border-slate-700">Editar</a>
                                        <form id="delete-course-form-{{ $course->id }}" class="inline" method="POST" action="{{ route('courses.destroy', $course) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-delete-title="{{ $course->name }}" data-form-id="delete-course-form-{{ $course->id }}" class="delete-confirm-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($courses->hasPages())
                    <div class="mt-6">
                        {{ $courses->links() }}
                    </div>
                @endif
            @endif
        </div>

        <div class="rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-4 text-sm text-slate-700 dark:text-slate-300">
            <span class="font-semibold">ℹ️ Nota:</span> O progresso dos cursos é definido pelo usuário e representa o percentual estimado de conteúdo já estudado. Isso permite acompanhar a evolução dos estudos ao longo do tempo.
        </div>

    </div>
</div>

@endsection
