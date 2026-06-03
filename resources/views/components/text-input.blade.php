@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-md bg-white/5 border border-white/6 text-white placeholder-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent transition']) }}>
