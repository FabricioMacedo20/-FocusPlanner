<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center w-full justify-center px-4 py-2 rounded-md font-semibold text-sm text-white uppercase tracking-wider shadow-md bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
