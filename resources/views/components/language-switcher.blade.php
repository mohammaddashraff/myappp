@props(['variant' => 'light'])

@php
    $currentLocale = app()->getLocale();
    $targetLocale = $currentLocale === 'ar' ? 'en' : 'ar';
    $variantClasses = $variant === 'dark'
        ? 'border-slate-200 bg-white text-slate-950 shadow-sm hover:bg-yellow-100'
        : 'border-slate-200 bg-white text-slate-950 shadow-sm hover:bg-slate-50';
@endphp

<a href="{{ route('locale.switch', $targetLocale) }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-black transition '.$variantClasses]) }}>
    {{ $targetLocale === 'ar' ? 'العربية' : 'English' }}
</a>
