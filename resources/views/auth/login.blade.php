<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('app.log_in') }} | {{ __('app.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|noto-sans-arabic:400,500,700&display=swap" rel="stylesheet" />

        <x-theme-script />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <main class="page-shell auth-stage">
            <div class="page-grid pointer-events-none absolute inset-0 opacity-40"></div>
            <section class="relative mx-auto flex min-h-screen max-w-xl flex-col justify-center px-4 py-6 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-slate-950 dark:text-slate-100">
                        <span class="flex size-12 items-center justify-center rounded-[1.35rem] bg-slate-950 text-xl font-black text-yellow-300 shadow-sm ring-4 ring-yellow-300/30">ط</span>
                        <span>
                            <span class="block text-2xl font-extrabold">{{ __('app.brand') }}</span>
                            <span class="block text-xs font-bold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-400">{{ __('app.brand_subtitle') }}</span>
                        </span>
                    </a>
                    <div class="flex items-center gap-2">
                        <x-theme-toggle />
                        <x-language-switcher variant="dark" />
                    </div>
                </div>

                <section class="frame-panel rounded-[2rem] p-5 text-slate-950 dark:text-slate-100 sm:p-8 lg:p-10">
                    <div class="border-b border-slate-200 pb-6">
                        <p class="section-label">{{ __('app.welcome_back') }}</p>
                        <h2 class="mt-1 text-3xl font-black uppercase text-slate-950">{{ __('app.log_in') }}</h2>
                    </div>

                    <x-auth-session-status class="mt-6" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('app.email')" />
                            <x-text-input id="email" class="mt-2 block w-full rounded-xl" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" dir="ltr" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <x-input-label for="password" :value="__('app.password')" />

                                @if (Route::has('password.request'))
                                    <a class="text-sm font-bold text-teal-700 transition hover:text-teal-900" href="{{ route('password.request') }}">
                                        {{ __('app.forgot') }}
                                    </a>
                                @endif
                            </div>

                            <x-text-input id="password" class="mt-2 block w-full rounded-xl" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <label for="remember_me" class="flex items-center gap-3 rounded-[1.25rem] border border-slate-200 bg-stone-50 px-4 py-3">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                            <span class="text-sm font-semibold text-slate-700">{{ __('app.remember_me') }}</span>
                        </label>

                        <button type="submit" class="button-brand w-full">
                            {{ __('app.log_in') }}
                        </button>
                    </form>

                    <div class="mt-7 rounded-[1.5rem] border border-slate-200 bg-stone-50 p-5">
                        <p class="text-sm font-bold text-slate-700">{{ __('app.new_here') }}</p>
                        <a href="{{ route('register') }}" class="button-muted mt-3 w-full">
                            {{ __('app.create_account') }}
                        </a>
                    </div>
                </section>
            </section>
        </main>
    </body>
</html>
