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

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Estilos de seleção de texto e logo tipográfica -->
    <style>
        ::selection {
            background-color: #3b82f6;
            color: white;
        }
        ::-moz-selection {
            background-color: #3b82f6;
            color: white;
        }

        .logo-heading {
            display: inline-block;
            position: relative;
            color: transparent;
            background-image: linear-gradient(120deg, #2563eb 0%, #38bdf8 55%, #60a5fa 100%);
            -webkit-background-clip: text;
            background-clip: text;
            text-shadow: 0 2px 12px rgba(59, 130, 246, 0.22);
            letter-spacing: 0.08em;
            transition: text-shadow 250ms ease, transform 250ms ease;
        }

        .dark .logo-heading {
            background-image: linear-gradient(120deg, #93c5fd 0%, #60a5fa 55%, #38bdf8 100%);
            text-shadow: 0 2px 12px rgba(56, 189, 248, 0.24);
        }

        .logo-heading::after {
            content: none;
        }

        .logo-heading:hover {
            text-shadow: 0 2px 18px rgba(59, 130, 246, 0.32), 0 0 18px rgba(56, 189, 248, 0.18);
            transform: translateY(-1px);
        }

        .logo-subtitle {
            color: #475569;
        }

        .dark .logo-subtitle {
            color: #94a3b8;
        }
    </style>
</head>

<body class="bg-light-bg dark:bg-slate-900 text-slate-900 dark:text-slate-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR - Design simples e funcional -->
    <nav class="w-64 bg-light-card dark:bg-slate-800 border-r border-light-border dark:border-slate-700 p-6 shadow-lg lg:fixed lg:inset-y-0 lg:left-0 lg:h-screen lg:overflow-y-auto">

        <!-- Logo -->
        <div class="mb-8">
            <h1 class="logo-heading text-3xl font-bold leading-tight tracking-[0.07em]"
                data-text="FocusPlanner">
                FocusPlanner
            </h1>
            <p class="logo-subtitle text-xs font-normal mt-1">Sistema de Produtividade</p>
        </div>

        <!-- Menu de navegação -->
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    <i class="ph ph-chart-bar w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('planner') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('planner') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    <i class="ph ph-calendar-blank w-5 h-5"></i>
                    <span>Planner</span>
                </a>
            </li>
            <li>
                <a href="{{ route('habits.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('habits.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    <i class="ph ph-repeat w-5 h-5"></i>
                    <span>Hábitos</span>
                </a>
            </li>
            <li>
                <a href="{{ route('goals.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('goals.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    <i class="ph ph-target w-5 h-5"></i>
                    <span>Metas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('courses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('courses.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    <i class="ph ph-books w-5 h-5"></i>
                    <span>Cursos</span>
                </a>
            </li>
            <li>
                <a href="{{ route('readings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('readings.index') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    <i class="ph ph-book-open-text w-5 h-5"></i>
                    <span>Leitura</span>
                </a>
            </li>
            <li>
                <a href="{{ route('relatorio') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-slate-700 font-medium {{ request()->routeIs('relatorio') ? 'bg-blue-100 dark:bg-slate-700 text-blue-700 dark:text-blue-300' : '' }}">
                    <i class="ph ph-chart-line-up w-5 h-5"></i>
                    <span>Relatório</span>
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
                    <i class="ph ph-sign-out w-5 h-5"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-1 p-10 bg-light-bg dark:bg-slate-950 lg:ml-64">

        <div class="max-w-7xl mx-auto space-y-8">
            @yield('content')
        </div>

    </main>

</div>

<div id="delete-confirm-overlay" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50"></div>
<div id="delete-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl p-6 transform transition-all duration-300 scale-95 opacity-0" id="delete-confirm-card">
        <div class="flex items-start gap-4">
            <div class="rounded-2xl bg-red-500/10 text-red-700 dark:text-red-300 p-3">
                <i class="ph ph-warning w-6 h-6"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <i class="ph ph-warning w-6 h-6 text-red-600 dark:text-red-400"></i>
                    Excluir Item
                </h3>
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-6">
                    Tem certeza que deseja excluir este item?
                </p>
                <p class="mt-3 text-base font-semibold text-slate-900 dark:text-slate-100" id="delete-confirm-title">"Título do item"</p>
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-6">
                    Esta ação removerá permanentemente o item selecionado e não poderá ser desfeita.
                </p>
            </div>
        </div>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button id="delete-confirm-cancel" type="button" class="rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center justify-center gap-2">
                <i class="ph ph-x w-5 h-5"></i>
                <span>Cancelar</span>
            </button>
            <button id="delete-confirm-submit" type="button" class="rounded-2xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition flex items-center justify-center gap-2">
                <i class="ph ph-trash w-5 h-5"></i>
                <span>Excluir</span>
            </button>
        </div>
    </div>
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

    const deleteButtons = document.querySelectorAll('.delete-confirm-button');
    const modal = document.getElementById('delete-confirm-modal');
    const overlay = document.getElementById('delete-confirm-overlay');
    const titleNode = document.getElementById('delete-confirm-title');
    const confirmButton = document.getElementById('delete-confirm-submit');
    const cancelButton = document.getElementById('delete-confirm-cancel');
    const card = document.getElementById('delete-confirm-card');
    let currentForm = null;

    function openModal(title, formId) {
        currentForm = document.getElementById(formId);
        titleNode.textContent = '"' + title + '"';
        modal.classList.remove('hidden');
        overlay.classList.remove('hidden');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
        });
    }

    function closeModal() {
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            overlay.classList.add('hidden');
        }, 200);
        currentForm = null;
    }

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            openModal(button.dataset.deleteTitle || 'Item', button.dataset.formId);
        });
    });

    confirmButton.addEventListener('click', () => {
        if (currentForm) {
            currentForm.submit();
        }
    });

    cancelButton.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
});
</script>

</body>

</html>
