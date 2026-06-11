<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fontes -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gradient-to-br from-[#041526] via-[#071a2b] to-[#000000]">
        @if(request()->routeIs('login'))
            <div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 py-10">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(255,255,255,0.02)_0%,_transparent_25%),radial-gradient(ellipse_at_bottom_right,_rgba(255,255,255,0.015)_0%,_transparent_20%)] pointer-events-none"></div>
                <div class="relative w-full max-w-[1000px] mx-auto">
                    <div class="rounded-2xl backdrop-blur-sm bg-white/5 shadow-2xl overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <!-- Painel de informações esquerdo -->
                            <div class="hidden md:flex flex-col gap-6 p-10 bg-[linear-gradient(135deg,rgba(10,25,40,0.3),rgba(5,10,20,0.25))]">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-sky-400 shadow-md">
                                        <!-- Ícone SVG -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                                            <circle cx="12" cy="12" r="9" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-2xl font-semibold">Focus Planner</h1>
                                        <p class="text-sm text-gray-300">Organize tarefas, aumente sua produtividade e mantenha o foco.</p>
                                    </div>
                                </div>

                                <ul class="mt-4 space-y-3">
                                    <li class="flex items-center gap-3 text-sm">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-white/6 text-sky-300">
                                            <!-- ícone -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                                            </svg>
                                        </span>
                                        Gestão de tarefas
                                    </li>
                                    <li class="flex items-center gap-3 text-sm">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-white/6 text-sky-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                                            </svg>
                                        </span>
                                        Planejamento eficiente
                                    </li>
                                    <li class="flex items-center gap-3 text-sm">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-white/6 text-sky-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                                <circle cx="12" cy="12" r="9" />
                                            </svg>
                                        </span>
                                        Controle de produtividade
                                    </li>
                                    <li class="flex items-center gap-3 text-sm">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-white/6 text-sky-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3" />
                                            </svg>
                                        </span>
                                        Dashboard intuitivo
                                    </li>
                                </ul>

                                <div class="mt-auto text-xs text-gray-400">Produto profissional para foco e produtividade.</div>
                            </div>

                            <!-- Painel de formulário direito -->
                            <div class="p-8 flex items-center justify-center">
                                <div class="w-full max-w-md">
                                    {{ $slot }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        @endif
    </body>
</html>
