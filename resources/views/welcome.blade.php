<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('rider.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <nav class="mx-auto flex max-w-7xl items-center justify-between rounded-lg border border-slate-200 bg-white px-4 py-4 shadow-sm sm:px-5">
                <a href="/" class="inline-flex items-center gap-3 text-slate-950">
                    <span class="flex size-11 items-center justify-center rounded-lg bg-yellow-300 text-lg font-black text-slate-950 shadow-sm">ط</span>
                    <span>
                        <span class="block text-xl font-extrabold">{{ __('rider.brand') }}</span>
                        <span class="block text-sm text-slate-500">{{ __('rider.brand_subtitle') }}</span>
                    </span>
                </a>

                @if (Route::has('login'))
                    <div class="flex items-center gap-2">
                        <x-language-switcher variant="dark" />
                        @auth
                            <a href="{{ route('rider.dashboard') }}" class="rounded-md bg-slate-950 px-4 py-2 text-sm font-black text-white transition hover:bg-slate-800">
                                {{ __('rider.rider_dashboard') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-md border border-slate-200 px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-slate-950">
                                    {{ __('rider.logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md border border-slate-200 px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-slate-950">{{ __('rider.login') }}</a>
                        @endauth
                    </div>
                @endif
            </nav>

            <section class="mx-auto grid max-w-7xl items-center gap-6 py-8 lg:grid-cols-[1fr_0.86fr] lg:py-12">
                <div class="max-w-3xl">
                    <p class="text-sm font-bold uppercase text-teal-700">
                        {{ __('rider.home_eyebrow') }}
                    </p>
                    <h1 class="mt-3 text-4xl font-black leading-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        {{ __('rider.home_headline') }}
                    </h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                        {{ __('rider.home_body') }}
                    </p>
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-slate-800">
                            {{ __('rider.home_start') }}
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex justify-center rounded-md border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-slate-950">
                            {{ __('rider.home_have_account') }}
                        </a>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-5 text-slate-950 shadow-sm">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
                            <div>
                                <p class="text-sm font-bold uppercase text-teal-700">{{ __('rider.home_card_label') }}</p>
                                <h2 class="mt-1 text-2xl font-black">{{ __('rider.home_card_title') }}</h2>
                            </div>
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-black text-yellow-800">{{ __('rider.ready') }}</span>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4">
                                <span class="flex size-10 items-center justify-center rounded-md bg-yellow-100 text-sm font-black text-yellow-800">01</span>
                                <div>
                                    <p class="font-bold">{{ __('rider.home_profile_title') }}</p>
                                    <p class="text-sm text-slate-500">{{ __('rider.home_profile_text') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4">
                                <span class="flex size-10 items-center justify-center rounded-md bg-teal-100 text-sm font-black text-teal-800">02</span>
                                <div>
                                    <p class="font-bold">{{ __('rider.home_garage_title') }}</p>
                                    <p class="text-sm text-slate-500">{{ __('rider.home_garage_text') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4">
                                <span class="flex size-10 items-center justify-center rounded-md bg-amber-100 text-sm font-black text-amber-800">03</span>
                                <div>
                                    <p class="font-bold">{{ __('rider.home_work_title') }}</p>
                                    <p class="text-sm text-slate-500">{{ __('rider.home_work_text') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
