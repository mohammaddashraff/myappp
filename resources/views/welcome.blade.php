<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('app.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|noto-sans-arabic:400,500,700&display=swap" rel="stylesheet" />

        <x-theme-script />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-stone-50 font-sans text-slate-950 antialiased dark:bg-slate-950 dark:text-slate-100">
        <main class="page-shell min-h-screen">
            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                <nav class="flex items-center justify-between border-b border-slate-200 pb-5">
                    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="inline-flex min-w-0 items-center gap-3 text-slate-950 dark:text-slate-100">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-slate-950 text-lg font-black text-yellow-300 shadow-sm ring-4 ring-yellow-300/25">ط</span>
                    <span class="min-w-0">
                        <span class="block text-base font-black leading-tight">{{ __('app.brand') }}</span>
                        <span class="block truncate text-xs font-black uppercase text-slate-500 dark:text-slate-400">{{ __('app.brand_subtitle') }}</span>
                    </span>
                    </a>

                @if (Route::has('login'))
                    <div class="flex items-center gap-2">
                        <x-theme-toggle />
                        <x-language-switcher variant="dark" />
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white transition hover:bg-slate-800">
                                {{ __('app.dashboard') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-slate-400 hover:text-slate-950">
                                    {{ __('app.log_out') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-slate-400 hover:text-slate-950">{{ __('app.log_in') }}</a>
                        @endauth
                    </div>
                @endif
                </nav>

            <section class="grid min-h-[calc(100vh-5rem)] gap-8 py-8 lg:grid-cols-[0.82fr_1.18fr] lg:items-center">
                <div class="max-w-2xl">
                    <p class="inline-flex rounded-full border border-yellow-200 bg-yellow-50 px-3 py-1 text-sm font-black text-yellow-900">
                        {{ __('app.home_eyebrow') }}
                    </p>
                    <h1 class="mt-4 text-4xl font-black leading-tight text-slate-950 sm:text-5xl">
                        {{ __('app.home_headline') }}
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-7 text-slate-600">
                        {{ __('app.home_body') }}
                    </p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('ads.index') }}" class="inline-flex justify-center rounded-lg bg-yellow-300 px-5 py-3 text-sm font-black text-slate-950 transition hover:bg-yellow-200">
                            {{ __('app.browse_ads') }}
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex justify-center rounded-lg bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                            {{ __('app.home_start') }}
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:border-slate-400 hover:text-slate-950">
                            {{ __('app.home_have_account') }}
                        </a>
                    </div>

                    <dl class="mt-8 grid max-w-xl gap-4 border-y border-slate-200 py-5 sm:grid-cols-3">
                        <div>
                            <dt class="text-xs font-black uppercase text-slate-500">{{ __('app.market_live') }}</dt>
                            <dd class="mt-1 text-2xl font-black">24/7</dd>
                            <p class="mt-1 text-sm leading-5 text-slate-600">{{ __('app.home_profile_text') }}</p>
                        </div>
                        <div>
                            <dt class="text-xs font-black uppercase text-slate-500">{{ __('app.fast_lane') }}</dt>
                            <dd class="mt-1 text-2xl font-black">3</dd>
                            <p class="mt-1 text-sm leading-5 text-slate-600">{{ __('app.home_subscription_text') }}</p>
                        </div>
                        <div>
                            <dt class="text-xs font-black uppercase text-slate-500">{{ __('app.direct_access') }}</dt>
                            <dd class="mt-1 text-2xl font-black">1</dd>
                            <p class="mt-1 text-sm leading-5 text-slate-600">{{ __('app.home_contact_text') }}</p>
                        </div>
                    </dl>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5">
                    <div class="border-b border-slate-200 bg-slate-950 p-5 text-white">
                        <p class="text-xs font-black uppercase text-yellow-300">{{ __('app.subscription_access') }}</p>
                        <h2 class="mt-2 text-2xl font-black">{{ __('app.access_panel_title') }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ __('app.access_panel_body') }}</p>
                    </div>

                    <div class="grid gap-5 p-5 lg:grid-cols-2">
                        @foreach ([
                            ['title' => __('app.individual'), 'slots' => '1', 'body' => __('app.plan_individual_description'), 'tone' => 'bg-yellow-300'],
                            ['title' => __('app.business'), 'slots' => '5', 'body' => __('app.plan_business_description'), 'tone' => 'bg-teal-300'],
                        ] as $plan)
                            <div class="rounded-xl border border-slate-200 bg-stone-50 p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xl font-black text-slate-950">{{ $plan['title'] }}</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $plan['body'] }}</p>
                                    </div>
                                    <span class="flex size-14 shrink-0 items-center justify-center rounded-lg {{ $plan['tone'] }} text-2xl font-black text-slate-950">{{ $plan['slots'] }}</span>
                                </div>
                                <p class="mt-5 rounded-lg bg-white px-4 py-3 text-sm font-black text-slate-700">{{ __('app.active_ads_label', ['count' => $plan['slots'], 'label' => __('app.ads')]) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-200 bg-stone-50 p-5">
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach ([
                                __('app.contact_visibility_included'),
                                __('app.publish_until_limit'),
                                __('app.sold_ads_free_slots'),
                            ] as $feature)
                                <div class="rounded-lg border border-slate-200 bg-white p-4">
                                    <span class="inline-flex size-8 items-center justify-center rounded-md bg-slate-950 text-sm font-black text-yellow-300">✓</span>
                                    <p class="mt-3 text-sm font-black leading-5 text-slate-800">{{ $feature }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('register') }}" class="inline-flex flex-1 justify-center rounded-lg bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                                {{ __('app.home_start') }}
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex flex-1 justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-700 transition hover:border-slate-400 hover:text-slate-950">
                                {{ __('app.home_have_account') }}
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 border-t border-slate-200 py-8 md:grid-cols-3">
                @foreach ([
                    ['title' => __('app.motorcycle'), 'body' => __('app.motorcycle_category_body')],
                    ['title' => __('app.part'), 'body' => __('app.part_category_body')],
                    ['title' => __('app.accessory'), 'body' => __('app.accessory_category_body')],
                ] as $category)
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-lg font-black text-slate-950">{{ $category['title'] }}</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $category['body'] }}</p>
                    </div>
                @endforeach
            </section>
            </div>
        </main>
    </body>
</html>
