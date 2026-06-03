<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('rider.brand') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <div class="flex min-h-screen flex-col items-center bg-slate-50 px-5 pt-6 sm:justify-center sm:pt-0">
            <div class="flex w-full max-w-md items-center justify-between">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-slate-950">
                    <span class="flex size-12 items-center justify-center rounded-lg bg-yellow-300 text-xl font-black text-slate-950 shadow-sm">ط</span>
                    <span>
                        <span class="block text-2xl font-extrabold">{{ __('rider.brand') }}</span>
                        <span class="block text-sm text-slate-500">{{ __('rider.brand_subtitle') }}</span>
                    </span>
                </a>

                <x-language-switcher variant="dark" />
            </div>

            <div class="mt-6 w-full overflow-hidden rounded-lg border border-slate-200 bg-white px-6 py-5 text-slate-950 shadow-sm sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
