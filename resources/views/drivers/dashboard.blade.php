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
        default => 'bg-zinc-100 text-zinc-700',
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
    <body class="bg-zinc-950 font-sans text-white antialiased">
        <main class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.18),_transparent_34rem),linear-gradient(135deg,_#09090b,_#111827_52%,_#172554)] px-5 py-8 sm:px-6 lg:px-8">
            <nav class="mx-auto flex max-w-6xl items-center justify-between">
                <a href="{{ route('drivers.dashboard') }}" class="inline-flex items-center gap-3">
                    <span class="flex size-11 items-center justify-center rounded-2xl bg-teal-400 text-lg font-black text-zinc-950 shadow-lg shadow-teal-950/40">ط</span>
                    <span>
                        <span class="block text-xl font-extrabold tracking-wide">طياران</span>
                        <span class="block text-sm text-teal-100/80">لوحة السائق</span>
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl border border-white/15 px-4 py-2 text-sm font-bold text-zinc-200 transition hover:bg-white/10 hover:text-white">
                        تسجيل الخروج
                    </button>
                </form>
            </nav>

            <section class="mx-auto grid min-h-[calc(100vh-8rem)] max-w-6xl items-center gap-8 py-12 lg:grid-cols-[0.9fr_1.1fr]">
                <div>
                    <p class="inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-sm font-bold text-teal-100">
                        مساحة السائق
                    </p>
                    <h1 class="mt-6 text-4xl font-black leading-tight sm:text-5xl">
                        لوحة السائق الخاصة بك قيد التجهيز
                    </h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-zinc-300 sm:text-lg">
                        نجهز أدوات طلبات التوصيل المباشرة، الأرباح، حالة الموقع، وتحديثات الطلب. حاليا يمكنك متابعة حالة طلبك من هنا.
                    </p>
                </div>

                <section class="rounded-3xl border border-white/10 bg-white p-6 text-zinc-950 shadow-2xl shadow-black/30 sm:p-8">
                    <div class="flex flex-col gap-4 border-b border-zinc-200 pb-6 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide text-teal-700">حالة الطلب</p>
                            <h2 class="mt-1 text-3xl font-black text-zinc-950">{{ $statusLabel }}</h2>
                        </div>

                        <span class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-black {{ $statusClasses }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    @if ($driverApplication)
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                <p class="text-xs font-black uppercase tracking-wide text-zinc-500">رقم الطلب</p>
                                <p class="mt-2 text-base font-bold text-zinc-950">#{{ $driverApplication->id }}</p>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                <p class="text-xs font-black uppercase tracking-wide text-zinc-500">تاريخ الإرسال</p>
                                <p class="mt-2 text-base font-bold text-zinc-950">{{ $driverApplication->submitted_at?->format('Y/m/d') ?? 'حديثا' }}</p>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                <p class="text-xs font-black uppercase tracking-wide text-zinc-500">السائق</p>
                                <p class="mt-2 text-base font-bold text-zinc-950">{{ $driverApplication->legal_name }}</p>
                            </div>

                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                <p class="text-xs font-black uppercase tracking-wide text-zinc-500">رقم اللوحة</p>
                                <p class="mt-2 text-base font-bold text-zinc-950">{{ $driverApplication->plate_number }}</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl bg-zinc-950 p-5 text-white">
                            <p class="text-sm font-black uppercase tracking-wide text-teal-200">الخطوة التالية</p>
                            <p class="mt-3 text-sm leading-6 text-zinc-300">
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
                        <div class="mt-6 rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                            <p class="text-base font-bold text-zinc-950">لا يوجد طلب سائق مرتبط بهذا الحساب حتى الآن.</p>
                            <p class="mt-2 text-sm leading-6 text-zinc-600">
                                ابدأ تسجيل السائق عندما تكون جاهزا لإرسال بيانات الهوية والتواصل والموتوسيكل.
                            </p>
                            <a href="{{ route('drivers.signup.create') }}" class="mt-5 inline-flex justify-center rounded-xl bg-teal-500 px-5 py-3 text-sm font-black text-zinc-950 transition hover:bg-teal-400">
                                ابدأ تسجيل السائق
                            </a>
                        </div>
                    @endif
                </section>
            </section>
        </main>
    </body>
</html>
