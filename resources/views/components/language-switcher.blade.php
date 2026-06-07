@props(['variant' => 'light'])

@php
    $currentLocale = app()->getLocale();
    $targetLocale = $currentLocale === 'ar' ? 'en' : 'ar';
    $variantClasses = $variant === 'dark'
        ? 'border-slate-200 bg-white text-slate-950 shadow-sm hover:bg-yellow-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800'
        : 'border-slate-200 bg-white text-slate-950 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800';
@endphp

<a href="{{ route('locale.switch', $targetLocale) }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-md border px-4 py-2 text-sm font-black transition '.$variantClasses]) }}>
    {{ $targetLocale === 'ar' ? 'العربية' : 'English' }}
</a>
