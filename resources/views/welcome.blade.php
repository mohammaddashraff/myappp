<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'طياران') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 font-sans text-white antialiased">
        <main class="min-h-screen bg-[radial-gradient(circle_at_top_right,_rgba(20,184,166,0.18),_transparent_30rem),linear-gradient(135deg,_#09090b_0%,_#111827_48%,_#172554_100%)]">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-6 sm:px-6 lg:px-8">
                <a href="/" class="inline-flex items-center gap-3">
                    <span class="flex size-11 items-center justify-center rounded-2xl bg-teal-400 text-lg font-black text-zinc-950 shadow-lg shadow-teal-950/40">ط</span>
                    <span>
                        <span class="block text-xl font-extrabold tracking-wide">طياران</span>
                        <span class="block text-sm text-teal-100/80">توصيل بالموتوسيكلات في مصر</span>
                    </span>
                </a>

                @if (Route::has('login'))
                    <div class="flex items-center gap-2">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-xl px-4 py-2 text-sm font-bold text-zinc-200 transition hover:bg-white/10 hover:text-white">
                                    تسجيل الخروج
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="rounded-xl px-4 py-2 text-sm font-bold text-zinc-200 transition hover:bg-white/10 hover:text-white">تسجيل الدخول</a>
                        @endauth
                    </div>
                @endif
            </nav>

            <section class="mx-auto grid max-w-7xl items-center gap-10 px-5 pb-14 pt-8 sm:px-6 lg:grid-cols-[1fr_0.82fr] lg:px-8 lg:pb-20 lg:pt-16">
                <div class="max-w-3xl">
                    <p class="mb-5 inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-sm font-semibold text-teal-100">
                        باب تسجيل السائقين مفتوح
                    </p>
                    <h1 class="text-5xl font-black leading-tight text-white sm:text-6xl lg:text-7xl">
                        توصيل بالموتوسيكلات مصمم لمصر.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-200">
                        المطاعم تنشئ الطلبات والسائقون المعتمدون القريبون يستقبلون طلبات التوصيل. طياران يبدأ من تسجيل سائقين موثقين.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('drivers.signup.create') }}" class="inline-flex justify-center rounded-xl bg-teal-400 px-6 py-3 text-sm font-black text-zinc-950 shadow-lg shadow-teal-950/30 transition hover:bg-teal-300">
                            سجل كسائق
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl border border-white/15 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                            دخول المطاعم أو الإدارة
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-2xl shadow-black/30 backdrop-blur">
                    <div class="rounded-2xl bg-white p-6 text-zinc-950">
                        <div class="flex items-center justify-between border-b border-zinc-200 pb-5">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide text-teal-700">قائمة المراجعة</p>
                                <h2 class="mt-1 text-2xl font-black">مراجعة السائق</h2>
                            </div>
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-800">قيد المراجعة</span>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-center gap-4 rounded-2xl bg-zinc-50 p-4">
                                <span class="flex size-10 items-center justify-center rounded-xl bg-teal-100 text-sm font-black text-teal-800">ID</span>
                                <div>
                                    <p class="font-bold">الهوية والصورة الشخصية</p>
                                    <p class="text-sm text-zinc-500">الرقم القومي، تاريخ الميلاد، وبيانات التواصل</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 rounded-2xl bg-zinc-50 p-4">
                                <span class="flex size-10 items-center justify-center rounded-xl bg-blue-100 text-sm font-black text-blue-800">MC</span>
                                <div>
                                    <p class="font-bold">بيانات الموتوسيكل</p>
                                    <p class="text-sm text-zinc-500">اللوحة، الشاسيه، الموتور، والمالك</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 rounded-2xl bg-zinc-50 p-4">
                                <span class="flex size-10 items-center justify-center rounded-xl bg-rose-100 text-sm font-black text-rose-800">OK</span>
                                <div>
                                    <p class="font-bold">مراجعة الالتزام</p>
                                    <p class="text-sm text-zinc-500">الرخص، الفيش، وصورة تحليل المخدرات</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
