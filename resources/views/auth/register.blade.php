<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('app.create_account') }} | {{ __('app.brand') }}</title>

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
                        <p class="section-label">{{ __('app.new_account') }}</p>
                        <h2 class="mt-1 text-3xl font-black uppercase text-slate-950">{{ __('app.create_account') }}</h2>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('app.name')" />
                            <x-text-input id="name" class="mt-2 block w-full rounded-xl" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('app.email')" />
                            <x-text-input id="email" class="mt-2 block w-full rounded-xl" type="email" name="email" :value="old('email')" required autocomplete="username" dir="ltr" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <x-input-label for="password" :value="__('app.password')" />
                                <x-text-input id="password" class="mt-2 block w-full rounded-xl" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('app.confirm_password')" />
                                <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-xl" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <button type="submit" class="button-brand w-full">
                            {{ __('app.create_account') }}
                        </button>
                    </form>

                    <div class="mt-7 rounded-[1.5rem] border border-slate-200 bg-stone-50 p-5">
                        <p class="text-sm font-bold text-slate-700">{{ __('app.already_have_account') }}</p>
                        <a href="{{ route('login') }}" class="button-muted mt-3 w-full">
                            {{ __('app.log_in') }}
                        </a>
                    </div>
                </section>
            </section>
        </main>
    </body>
</html>
