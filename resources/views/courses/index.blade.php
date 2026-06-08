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
                                        <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Acessar Matéria</a>
                                        <a href="{{ route('courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700">Editar</a>
                                        <form class="inline" method="POST" action="{{ route('courses.destroy', $course) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-200 border border-slate-200 dark:border-slate-700" onclick="return confirm('Tem certeza que deseja excluir este curso?')">Excluir</button>
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

@endsection
