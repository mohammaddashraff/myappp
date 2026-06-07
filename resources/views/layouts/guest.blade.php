<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ trim($__env->yieldContent('title', __('app.brand'))) }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|noto-sans-arabic:400,500,700&display=swap" rel="stylesheet" />

        <x-theme-script />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="page-shell flex min-h-screen flex-col items-center px-5 pt-6 sm:justify-center sm:pt-0">
            <div class="relative flex w-full max-w-md items-center justify-between">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="inline-flex min-w-0 items-center gap-3 text-slate-950 dark:text-slate-100">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-slate-950 text-xl font-black text-yellow-300 shadow-sm ring-4 ring-yellow-300/30">ط</span>
                    <span>
                        <span class="block text-2xl font-extrabold">{{ __('app.brand') }}</span>
                        <span class="block text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('app.brand_subtitle') }}</span>
                    </span>
                </a>

                <div class="flex items-center gap-2">
                    <x-theme-toggle />
                    <x-language-switcher variant="dark" />
                </div>
            </div>

            <div class="frame-panel relative mt-6 w-full overflow-hidden px-6 py-5 text-slate-950 dark:text-slate-100 sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
