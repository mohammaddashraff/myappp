<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-rose-600 px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 active:bg-rose-700']) }}>
    {{ $slot }}
</button>
