<div {{ $attributes->merge(['class' => 'bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/40 dark:to-cyan-900/40 rounded-lg p-8 shadow-md border border-blue-200 dark:border-slate-700']) }}>
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-4xl font-bold mt-2 text-slate-900 dark:text-white">{{ $title }}</h1>
            <p class="text-slate-900 dark:text-blue-300 mt-2">{{ $description }}</p>
        </div>
        <div class="text-right">
            {{ $slot }}
        </div>
    </div>
</div>
