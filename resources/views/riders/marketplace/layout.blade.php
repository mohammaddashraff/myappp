<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Marketplace') | {{ __('rider.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <section class="mx-auto grid max-w-7xl gap-6 md:grid-cols-[240px_minmax(0,1fr)] xl:grid-cols-[280px_minmax(0,1fr)]">
                @php
                    $marketplaceActive = trim($__env->yieldContent('marketplaceActive', trim($__env->yieldContent('active', 'marketplace'))));
                @endphp

                <div class="hidden md:block">
                    @include('riders.partials.sidebar', ['active' => 'marketplace', 'showAddButton' => false])
                </div>

                <div class="py-2 lg:py-4" dir="ltr">
                    @include('riders.marketplace.partials.section-nav', ['active' => $marketplaceActive])
                    @include('riders.marketplace.partials.flash')
                    @yield('content')
                </div>
            </section>
        </main>
    </body>
</html>
