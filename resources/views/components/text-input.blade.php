@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md border-slate-300 text-slate-950 shadow-sm focus:border-teal-500 focus:ring-teal-500 disabled:bg-slate-100 disabled:text-slate-500']) }}>
