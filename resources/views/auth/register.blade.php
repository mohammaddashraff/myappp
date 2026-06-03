<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('rider.register_title') }} | {{ __('rider.brand') }}</title>

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
                            {{ __('rider.register_eyebrow') }}
                        </p>
                        <h1 class="mt-3 text-4xl font-black leading-tight text-slate-950 sm:text-5xl">
                            {{ __('rider.register_headline') }}
                        </h1>
                        <p class="mt-4 max-w-lg text-base leading-7 text-slate-600 sm:text-lg">
                            {{ __('rider.register_body') }}
                        </p>
                    </div>
                </aside>

                <section class="rounded-lg border border-slate-200 bg-white p-5 text-slate-950 shadow-sm sm:p-8 lg:p-10">
                    <div class="border-b border-slate-200 pb-6">
                        <p class="text-sm font-bold uppercase text-teal-700">{{ __('rider.new_account') }}</p>
                        <h2 class="mt-1 text-3xl font-black text-slate-950">{{ __('rider.register_title') }}</h2>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('rider.full_name')" />
                            <x-text-input id="name" class="mt-2 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('rider.email')" />
                            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" dir="ltr" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <x-input-label for="password" :value="__('rider.password')" />
                                <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('rider.password_confirmation')" />
                                <x-text-input id="password_confirmation" class="mt-2 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            {{ __('rider.create_rider_account') }}
                        </button>
                    </form>

                    <div class="mt-7 rounded-lg border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm font-bold text-slate-700">{{ __('rider.already_have_account') }}</p>
                        <a href="{{ route('login') }}" class="mt-3 inline-flex w-full justify-center rounded-md border border-slate-200 bg-white px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-slate-100 hover:text-slate-950">
                            {{ __('rider.login') }}
                        </a>
                    </div>
                </section>
            </section>
        </main>
    </body>
</html>
