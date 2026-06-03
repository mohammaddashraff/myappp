<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>حالة الطلب | {{ config('app.name', 'طياران') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <section class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-5xl items-center">
                <div class="w-full overflow-hidden rounded-lg border border-slate-200 bg-white text-slate-950 shadow-sm">
                    <div class="grid gap-0 lg:grid-cols-[0.9fr_1.1fr]">
                        <aside class="border-b border-slate-200 bg-slate-50 p-8 text-slate-950 sm:p-10 lg:border-b-0 lg:border-e">
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                                <span class="flex size-11 items-center justify-center rounded-lg bg-yellow-300 text-lg font-black text-slate-950 shadow-sm">ط</span>
                                <span>
                                    <span class="block text-xl font-extrabold">طياران</span>
                                    <span class="block text-sm text-slate-500">تسجيل السائقين</span>
                                </span>
                            </a>

                            <div class="mt-16 max-w-sm">
                                <p class="text-sm font-bold uppercase text-teal-700">
                                    قيد المراجعة
                                </p>
                                <h1 class="mt-3 text-4xl font-black leading-tight text-slate-950 sm:text-5xl">طلبك قيد المراجعة</h1>
                                <p class="mt-4 text-base leading-7 text-slate-600">
                                    يقوم فريق التشغيل بمراجعة الهوية والرخصة والمركبة ومستندات السلامة.
                                </p>
                            </div>
                        </aside>

                        <div class="p-6 sm:p-8 lg:p-10">
                            <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-sm font-bold uppercase text-teal-700">طلب رقم #{{ $driver->id }}</p>
                                    <h2 class="mt-1 text-2xl font-black text-slate-950">{{ $driver->legal_name }}</h2>
                                    <p class="mt-2 text-sm text-slate-500">
                                        تم الإرسال {{ $driver->submitted_at?->format('Y/m/d') ?? 'حديثا' }}
                                    </p>
                                </div>

                                <span class="inline-flex w-fit rounded-full bg-amber-100 px-4 py-2 text-sm font-black text-amber-800">
                                    قيد المراجعة
                                </span>
                            </div>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs font-black uppercase text-slate-500">الهاتف</p>
                                    <p class="mt-2 text-base font-bold text-slate-950">{{ $driver->phone_number }}</p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs font-black uppercase text-slate-500">رقم اللوحة</p>
                                    <p class="mt-2 text-base font-bold text-slate-950">{{ $driver->plate_number }}</p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs font-black uppercase text-slate-500">مالك المركبة</p>
                                    <p class="mt-2 text-base font-bold text-slate-950">{{ $driver->vehicle_owner_name }}</p>
                                </div>

                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs font-black uppercase text-slate-500">جهة اتصال الطوارئ</p>
                                    <p class="mt-2 text-base font-bold text-slate-950">{{ $driver->emergency_contact_name }}</p>
                                </div>
                            </div>

                            <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 p-5 text-slate-950">
                                <p class="text-sm font-black uppercase text-amber-700">الخطوة التالية</p>
                                <p class="mt-3 text-sm leading-6 text-slate-700">
                                    يرجى إبقاء هاتفك متاحا. بعد موافقة الإدارة ستظهر حالة الطلب في لوحة السائق.
                                </p>
                            </div>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <a href="{{ route('home') }}" class="inline-flex justify-center rounded-md px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">
                                    الرئيسية
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800 sm:w-auto">
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
