@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full rounded-lg border border-slate-950 bg-slate-950 px-4 py-3 text-start text-base font-bold text-white transition focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2'
    : 'block w-full rounded-lg border border-transparent px-4 py-3 text-start text-base font-bold text-slate-600 transition hover:border-slate-300 hover:bg-white hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
