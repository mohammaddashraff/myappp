<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('rider.login') }} | {{ __('rider.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <section class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-6xl items-center gap-8 lg:grid-cols-[0.95fr_1.05fr]">
                <aside class="max-w-xl">
                    <div class="flex items-center justify-between gap-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-slate-950">
                            <span class="flex size-12 items-center justify-center rounded-lg bg-yellow-300 text-xl font-black text-slate-950 shadow-sm">ط</span>
                            <span>
                                <span class="block text-2xl font-extrabold">{{ __('rider.brand') }}</span>
                                <span class="block text-sm text-slate-500">{{ __('rider.brand_subtitle') }}</span>
                            </span>
                        </a>
                        <x-language-switcher variant="dark" />
                    </div>

                    <div class="mt-14">
                        <p class="text-sm font-bold uppercase text-teal-700">
                            {{ __('rider.login_eyebrow') }}
                        </p>
                        <h1 class="mt-3 text-4xl font-black leading-tight text-slate-950 sm:text-5xl">
                            {{ __('rider.login_headline') }}
                        </h1>
                        <p class="mt-4 max-w-lg text-base leading-7 text-slate-600 sm:text-lg">
                            {{ __('rider.login_body') }}
                        </p>
                    </div>
                </aside>

                <section class="rounded-lg border border-slate-200 bg-white p-5 text-slate-950 shadow-sm sm:p-8 lg:p-10">
                    <div class="border-b border-slate-200 pb-6">
                        <p class="text-sm font-bold uppercase text-teal-700">{{ __('rider.welcome_back') }}</p>
                        <h2 class="mt-1 text-3xl font-black text-slate-950">{{ __('rider.login') }}</h2>
                    </div>

                    <x-auth-session-status class="mt-6" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('rider.email')" />
                            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" dir="ltr" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <x-input-label for="password" :value="__('rider.password')" />

                                @if (Route::has('password.request'))
                                    <a class="text-sm font-bold text-teal-700 transition hover:text-teal-900" href="{{ route('password.request') }}">
                                        {{ __('rider.forgot') }}
                                    </a>
                                @endif
                            </div>

                            <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <label for="remember_me" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                            <span class="text-sm font-semibold text-slate-700">{{ __('rider.remember_me') }}</span>
                        </label>

                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            {{ __('rider.login') }}
                        </button>
                    </form>

                    <div class="mt-7 rounded-lg border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm font-bold text-slate-700">{{ __('rider.new_rider') }}</p>
                        <a href="{{ route('register') }}" class="mt-3 inline-flex w-full justify-center rounded-md border border-slate-200 bg-white px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-slate-100 hover:text-slate-950">
                            {{ __('rider.create_rider_account') }}
                        </a>
                    </div>
                </section>
            </section>
        </main>
    </body>
</html>
