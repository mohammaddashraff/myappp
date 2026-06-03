<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>تم استلام الطلب | {{ config('app.name', 'طياران') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10 sm:px-6 lg:px-8">
            <section class="w-full max-w-2xl rounded-lg border border-slate-200 bg-white p-8 text-center text-slate-950 shadow-sm sm:p-10">
                <div class="mx-auto flex size-16 items-center justify-center rounded-lg bg-yellow-300 text-3xl font-black text-slate-950 shadow-sm">ط</div>
                <p class="mt-6 text-sm font-bold uppercase text-teal-700">طياران</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950 sm:text-4xl">تم استلام الطلب</h1>
                <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-slate-600">
                    تم حفظ طلب تسجيل السائق ووضعه قيد المراجعة.
                    @if (session('driver_application_id'))
                        رقم الطلب #{{ session('driver_application_id') }}
                    @endif
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ route('drivers.signup.create', ['fresh' => 1]) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        طلب جديد
                    </a>
                    <a href="/" class="inline-flex justify-center rounded-md border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                        الرئيسية
                    </a>
                </div>
            </section>
        </main>
    </body>
</html>
