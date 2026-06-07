<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mobile Demo | {{ __('app.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|noto-sans-arabic:400,500,700&display=swap" rel="stylesheet" />

        <x-theme-script />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-stone-50 font-sans text-slate-950 antialiased dark:bg-slate-950 dark:text-slate-100">
        <main class="page-shell min-h-screen">
            <div class="mx-auto flex min-h-screen max-w-7xl flex-col justify-center gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:items-center lg:px-8">
                <section class="max-w-2xl">
                    <p class="editorial-kicker">Stakeholder Preview</p>
                    <h1 class="mt-4 text-4xl font-black leading-tight sm:text-5xl">
                        Mobile app concept for {{ __('app.brand') }}
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-7 text-slate-600 dark:text-slate-300">
                        A fast demo screen that shows how the marketplace can feel as a mobile-first experience before we build a full native app.
                    </p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        @foreach ([
                            ['label' => 'Browse', 'value' => '01', 'body' => 'Scroll featured bikes, parts, and accessories with thumb-friendly cards.'],
                            ['label' => 'Message', 'value' => '02', 'body' => 'Highlight direct seller contact and saved listings in one place.'],
                            ['label' => 'Sell', 'value' => '03', 'body' => 'Push subscription and listing actions into a simple mobile flow.'],
                        ] as $point)
                            <article class="frame-panel p-4">
                                <p class="text-xs font-black uppercase tracking-[0.28em] text-teal-700 dark:text-teal-300">{{ $point['label'] }}</p>
                                <p class="mt-3 text-2xl font-black">{{ $point['value'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $point['body'] }}</p>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('home') }}" class="button-accent">Back to website</a>
                        <a href="{{ route('register') }}" class="button-brand">Open sign-up flow</a>
                    </div>
                </section>

                <section class="mx-auto w-full max-w-md">
                    <div class="rounded-[2.75rem] border-[10px] border-slate-950 bg-slate-950 p-3 shadow-[0_35px_90px_rgba(15,23,42,0.35)]">
                        <div class="rounded-[2rem] bg-[radial-gradient(circle_at_top,_rgba(250,204,21,0.35),_transparent_40%),linear-gradient(180deg,_#fffdf7_0%,_#f8fafc_50%,_#e2e8f0_100%)] p-4">
                            <div class="mx-auto mb-4 h-1.5 w-24 rounded-full bg-slate-950/15"></div>

                            <header class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-500">Moto Ads App</p>
                                    <h2 class="mt-1 text-2xl font-black text-slate-950">Mobile Demo</h2>
                                </div>
                                <div class="flex size-12 items-center justify-center rounded-2xl bg-slate-950 text-lg font-black text-yellow-300 shadow-lg">
                                    ط
                                </div>
                            </header>

                            <div class="mt-5 rounded-3xl bg-slate-950 p-5 text-white shadow-xl shadow-slate-900/20">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.24em] text-yellow-300">Featured Today</p>
                                        <h3 class="mt-2 text-xl font-black">Yamaha NMAX 2024</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-300">Low-mileage scooter with delivery box, fresh tires, and verified seller profile.</p>
                                    </div>
                                    <span class="rounded-2xl bg-white/10 px-3 py-2 text-sm font-black">EGP 128k</span>
                                </div>

                                <div class="mt-5 grid grid-cols-3 gap-3">
                                    @foreach (['Auto', 'Verified', 'Nearby'] as $tag)
                                        <span class="rounded-2xl bg-white/10 px-3 py-2 text-center text-xs font-black">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <section class="mt-5">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-black uppercase tracking-[0.22em] text-slate-500">Quick Actions</h3>
                                    <span class="text-xs font-bold text-slate-500">Thumb-first</span>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-3">
                                    @foreach ([
                                        ['title' => 'Browse Ads', 'tone' => 'bg-yellow-300 text-slate-950'],
                                        ['title' => 'Saved Items', 'tone' => 'bg-teal-300 text-slate-950'],
                                        ['title' => 'Sell Now', 'tone' => 'bg-white text-slate-950'],
                                        ['title' => 'Messages', 'tone' => 'bg-white text-slate-950'],
                                    ] as $action)
                                        <article class="rounded-3xl {{ $action['tone'] }} p-4 shadow-sm">
                                            <p class="text-sm font-black">{{ $action['title'] }}</p>
                                            <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] opacity-70">Tap</p>
                                        </article>
                                    @endforeach
                                </div>
                            </section>

                            <section class="mt-5 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-black text-slate-950">Seller Confidence</p>
                                        <p class="mt-1 text-sm text-slate-500">What stakeholders should notice</p>
                                    </div>
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">Live feel</span>
                                </div>

                                <div class="mt-4 space-y-3">
                                    @foreach ([
                                        'Large tap targets for browsing and selling',
                                        'Clear price, trust, and location hierarchy',
                                        'Home-screen style navigation for repeat use',
                                    ] as $item)
                                        <div class="flex items-start gap-3 rounded-2xl bg-slate-50 px-3 py-3">
                                            <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-slate-950 text-xs font-black text-yellow-300">✓</span>
                                            <p class="text-sm font-semibold leading-6 text-slate-700">{{ $item }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </section>

                            <nav class="mt-5 grid grid-cols-4 gap-2 rounded-3xl bg-white p-3 shadow-sm ring-1 ring-slate-200">
                                @foreach ([
                                    ['label' => 'Home', 'active' => true],
                                    ['label' => 'Search', 'active' => false],
                                    ['label' => 'Sell', 'active' => false],
                                    ['label' => 'Inbox', 'active' => false],
                                ] as $item)
                                    <div class="rounded-2xl px-2 py-3 text-center {{ $item['active'] ? 'bg-slate-950 text-white' : 'text-slate-500' }}">
                                        <p class="text-xs font-black uppercase tracking-[0.18em]">{{ $item['label'] }}</p>
                                    </div>
                                @endforeach
                            </nav>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
