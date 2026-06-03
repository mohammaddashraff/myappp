@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-teal-500 bg-teal-50 py-2 pe-4 ps-3 text-start text-base font-medium text-teal-800 transition focus:outline-none focus:bg-teal-100 focus:text-teal-900'
            : 'block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-800 focus:outline-none focus:bg-slate-50 focus:text-slate-800';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
