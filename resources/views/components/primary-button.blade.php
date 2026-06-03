<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-md border border-transparent bg-slate-950 px-4 py-2 text-xs font-semibold uppercase text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 active:bg-slate-900']) }}>
    {{ $slot }}
</button>
