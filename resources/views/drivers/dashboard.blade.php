@php
    $status = $driverApplication?->approval_status;
    $statusLabel = match ($status) {
        'pending' => 'قيد المراجعة',
        'approved' => 'تمت الموافقة',
        'rejected' => 'مرفوض',
        default => 'لم يبدأ',
    };
    $statusClasses = match ($status) {
        'pending' => 'bg-amber-100 text-amber-800',
        'approved' => 'bg-teal-100 text-teal-800',
        'rejected' => 'bg-rose-100 text-rose-800',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>لوحة السائق | {{ config('app.name', 'طياران') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <nav class="mx-auto flex max-w-6xl items-center justify-between rounded-lg border border-slate-200 bg-white px-4 py-4 shadow-sm sm:px-5">
                <a href="{{ route('drivers.dashboard') }}" class="inline-flex items-center gap-3 text-slate-950">
                    <span class="flex size-11 items-center justify-center rounded-lg bg-yellow-300 text-lg font-black text-slate-950 shadow-sm">ط</span>
                    <span>
                        <span class="block text-xl font-extrabold">طياران</span>
                        <span class="block text-sm text-slate-500">لوحة السائق</span>
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-md border border-slate-200 px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-slate-950">
                        تسجيل الخروج
                    </button>
                </form>
            </nav>

            <section class="mx-auto grid max-w-6xl items-center gap-6 py-8 lg:grid-cols-[0.86fr_1.14fr]">
                <div>
                    <p class="text-sm font-bold uppercase text-teal-700">
                        مساحة السائق
                    </p>
                    <h1 class="mt-3 text-4xl font-black leading-tight text-slate-950 sm:text-5xl">
                        لوحة السائق الخاصة بك قيد التجهيز
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                        نجهز أدوات طلبات التوصيل المباشرة، الأرباح، حالة الموقع، وتحديثات الطلب. حاليا يمكنك متابعة حالة طلبك من هنا.
                    </p>
                </div>

                <section class="rounded-lg border border-slate-200 bg-white p-6 text-slate-950 shadow-sm sm:p-8">
                    <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-bold uppercase text-teal-700">حالة الطلب</p>
                            <h2 class="mt-1 text-3xl font-black text-slate-950">{{ $statusLabel }}</h2>
                        </div>

                        <span class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-black {{ $statusClasses }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    @if ($driverApplication)
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">رقم الطلب</p>
                                <p class="mt-2 text-base font-bold text-slate-950">#{{ $driverApplication->id }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">تاريخ الإرسال</p>
                                <p class="mt-2 text-base font-bold text-slate-950">{{ $driverApplication->submitted_at?->format('Y/m/d') ?? 'حديثا' }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">السائق</p>
                                <p class="mt-2 text-base font-bold text-slate-950">{{ $driverApplication->legal_name }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">رقم اللوحة</p>
                                <p class="mt-2 text-base font-bold text-slate-950">{{ $driverApplication->plate_number }}</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-5 text-slate-950">
                            <p class="text-sm font-black uppercase text-amber-700">الخطوة التالية</p>
                            <p class="mt-3 text-sm leading-6 text-slate-700">
                                @if ($status === 'pending')
                                    مستنداتك وبيانات الموتوسيكل ما زالت قيد المراجعة. يرجى إبقاء هاتفك متاحا لفريق التشغيل.
                                @elseif ($status === 'approved')
                                    تمت الموافقة على طلبك. ستظهر أدوات التوصيل والطلبات المباشرة هنا عندما تجهز مساحة السائق.
                                @else
                                    سيقوم فريق التشغيل بتحديث هذه الحالة عند حدوث أي تغيير في طلبك.
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 p-5">
                            <p class="text-base font-bold text-slate-950">لا يوجد طلب سائق مرتبط بهذا الحساب حتى الآن.</p>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                ابدأ تسجيل السائق عندما تكون جاهزا لإرسال بيانات الهوية والتواصل والموتوسيكل.
                            </p>
                            <a href="{{ route('drivers.signup.create') }}" class="mt-5 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                                ابدأ تسجيل السائق
                            </a>
                        </div>
                    @endif
                </section>
            </section>
        </main>
    </body>
</html>
