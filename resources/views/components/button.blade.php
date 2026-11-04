<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-full bg-orange-500 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
