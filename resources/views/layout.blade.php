<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusPlanner</title>

    <!-- Script para carregar tema ANTES de renderizar (evita flash branco) -->
    <script>
        const theme = localStorage.getItem('theme') || 'dark';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind CSS com suporte a darkMode usando classe 'dark' -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#080f1a',
                        'dark-card': '#0a1628',
                        'dark-border': '#1e293b',
                        'light-bg': '#f8fafc',
                        'light-card': '#ffffff',
                        'light-border': '#e2e8f0',
                    }
                }
            }
        }
    </script>

    <!-- Estilos de seleção de texto -->
    <style>
        ::selection {
            background-color: #3b82f6;
            color: white;
        }
        ::-moz-selection {
            background-color: #3b82f6;
            color: white;
        }
    </style>
</head>

<body class="bg-light-bg dark:bg-slate-900 text-slate-900 dark:text-slate-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR - Design simples e funcional -->
    <nav class="w-64 bg-light-card dark:bg-slate-800 border-r border-light-border dark:border-slate-700 p-6 shadow-lg">

        <!-- Logo -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                FocusPlanner
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sistema de Produtividade</p>
        </div>

        <!-- Menu de navegação -->
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    📊 Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('planner') }}" class="block px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('planner') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    📅 Planner
                </a>
            </li>
            <li>
                <a href="{{ route('habits.index') }}" class="block px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('habits.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    🔄 Hábitos
                </a>
            </li>
            <li>
                <a href="{{ route('goals.index') }}" class="block px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('goals.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    🎯 Metas
                </a>
            </li>
            <li>
                <a href="{{ route('courses.index') }}" class="block px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('courses.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    📚 Cursos
                </a>
            </li>
            <li>
                <a href="{{ route('readings.index') }}" class="block px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('readings.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    📖 Leitura
                </a>
            </li>
            <li>
                <a href="{{ route('notes.index') }}" class="block px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('notes.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    📝 Atividades do dia
                </a>
            </li>
            <li>
                <a href="{{ route('relatorio') }}" class="block px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('relatorio') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    📈 Relatório
                </a>
            </li>
        </ul>

        <!-- Botão de troca de tema -->
        <div class="mt-auto pt-6 border-t border-light-border dark:border-slate-700">
            <button id="themeToggle" class="w-full px-4 py-3 rounded-lg bg-light-border dark:bg-slate-700 text-slate-900 dark:text-slate-100 font-semibold hover:bg-blue-100 dark:hover:bg-slate-600 flex items-center justify-center gap-2">
                <span id="themeIcon">Modo Escuro</span>
                <span id="themeText">Dark Mode</span>
            </button>

            <!-- Botão de Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full px-4 py-3 rounded-lg bg-red-500 hover:bg-red-600 text-white font-semibold flex items-center justify-center gap-2">
                    🚪 Logout
                </button>
            </form>
        </div>

    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-1 p-10 bg-light-bg dark:bg-slate-950">

        <div class="max-w-7xl mx-auto space-y-8">
            @yield('content')
        </div>

    </main>

</div>

<!-- Script para gerenciar alternância de tema -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');

    function toggleTheme() {
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeUI();
    }

    function updateThemeUI() {
        const isDark = html.classList.contains('dark');
        if (isDark) {
            themeIcon.textContent = '🌙';
            themeText.textContent = 'Dark Mode';
        } else {
            themeIcon.textContent = '☀️';
            themeText.textContent = 'Light Mode';
        }
    }

    themeToggle.addEventListener('click', toggleTheme);
    updateThemeUI();
});
</script>

</body>

</html>
