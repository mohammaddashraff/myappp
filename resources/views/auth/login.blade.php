<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>تسجيل الدخول | {{ config('app.name', 'طياران') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 font-sans text-white antialiased">
        <main class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.18),_transparent_34rem),linear-gradient(135deg,_#09090b,_#111827_52%,_#172554)] px-5 py-8 sm:px-6 lg:px-8">
            <section class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-6xl items-center gap-8 lg:grid-cols-[0.95fr_1.05fr]">
                <aside class="max-w-xl">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <span class="flex size-12 items-center justify-center rounded-2xl bg-teal-400 text-xl font-black text-zinc-950 shadow-lg shadow-teal-950/40">ط</span>
                        <span>
                            <span class="block text-2xl font-extrabold tracking-wide">طياران</span>
                            <span class="block text-sm text-teal-100/80">توصيل بالموتوسيكلات في مصر</span>
                        </span>
                    </a>

                    <div class="mt-14">
                        <p class="inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-sm font-bold text-teal-100">
                            بوابة السائقين
                        </p>
                        <h1 class="mt-5 text-4xl font-black leading-tight sm:text-5xl lg:text-6xl">
                            سجل دخولك لمتابعة طلبك
                        </h1>
                        <p class="mt-5 max-w-lg text-base leading-7 text-zinc-300 sm:text-lg">
                            استخدم الحساب الذي أنشأته أثناء تسجيل السائق لمتابعة حالة المراجعة والبدء مع طياران بعد الموافقة.
                        </p>
                    </div>
                </aside>

                <section class="rounded-3xl border border-white/10 bg-white p-5 text-zinc-950 shadow-2xl shadow-black/30 sm:p-8 lg:p-10">
                    <div class="border-b border-zinc-200 pb-6">
                        <p class="text-sm font-bold uppercase tracking-wide text-teal-700">أهلا بعودتك</p>
                        <h2 class="mt-1 text-3xl font-black text-zinc-950">تسجيل الدخول</h2>
                    </div>

                    <x-auth-session-status class="mt-6" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="email" value="البريد الإلكتروني" />
                            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" dir="ltr" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <x-input-label for="password" value="كلمة المرور" />

                                @if (Route::has('password.request'))
                                    <a class="text-sm font-bold text-teal-700 transition hover:text-teal-900" href="{{ route('password.request') }}">
                                        نسيت؟
                                    </a>
                                @endif
                            </div>

                            <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <label for="remember_me" class="flex items-center gap-3 rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3">
                            <input id="remember_me" type="checkbox" class="rounded border-zinc-300 text-teal-600 shadow-sm focus:ring-teal-600" name="remember">
                            <span class="text-sm font-semibold text-zinc-700">تذكرني</span>
                        </label>

                        <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-zinc-950 px-5 py-3 text-sm font-black text-white shadow-lg shadow-zinc-950/20 transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            تسجيل الدخول
                        </button>
                    </form>

                    <div class="mt-7 rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                        <p class="text-sm font-bold text-zinc-700">سائق جديد؟</p>
                        <a href="{{ route('drivers.signup.create') }}" class="mt-3 inline-flex w-full justify-center rounded-xl bg-teal-500 px-5 py-3 text-sm font-black text-zinc-950 transition hover:bg-teal-400">
                            ابدأ تسجيل السائق
                        </a>
                    </div>
                </section>
            </section>
        </main>
    </body>
</html>
