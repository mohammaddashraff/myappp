@php
    $motorcycles = $rider?->motorcycles ?? collect();
    $documentsCount = $motorcycles->sum(fn ($motorcycle) => $motorcycle->documents->count());
    $status = $driverApplication?->approval_status;
    $statusLabel = match ($status) {
        'pending' => __('rider.status_pending'),
        'approved' => __('rider.status_approved'),
        'rejected' => __('rider.status_rejected'),
        default => __('rider.status_not_started'),
    };
    $statusClasses = match ($status) {
        'pending' => 'bg-amber-100 text-amber-800',
        'approved' => 'bg-teal-100 text-teal-800',
        'rejected' => 'bg-rose-100 text-rose-800',
        default => 'bg-slate-100 text-slate-700',
    };
    $nextStepLabel = match (true) {
        ! $rider => __('rider.next_create_profile'),
        $motorcycles->isEmpty() => __('rider.next_add_motorcycle'),
        $documentsCount === 0 => __('rider.next_add_documents'),
        default => __('rider.next_basic_ready'),
    };
    $nextStepHelp = match (true) {
        ! $rider => __('rider.help_create_profile'),
        $motorcycles->isEmpty() => __('rider.help_add_motorcycle'),
        $documentsCount === 0 => __('rider.help_add_documents'),
        default => __('rider.help_basic_ready'),
    };
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('rider.dashboard_title') }} | {{ __('rider.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <section class="mx-auto grid max-w-7xl gap-6 md:grid-cols-[240px_minmax(0,1fr)] xl:grid-cols-[280px_minmax(0,1fr)]">
                @include('riders.partials.sidebar', ['active' => 'dashboard'])

                <div class="grid gap-6 py-2 lg:grid-cols-[0.82fr_1.18fr] lg:py-4">
                    <div class="flex flex-col justify-center">
                        <p class="text-sm font-bold uppercase text-teal-700">
                            {{ __('rider.rider_core') }}
                        </p>
                        <h1 class="mt-2 text-3xl font-black leading-tight text-slate-950 sm:text-4xl">
                            {{ __('rider.hello_name', ['name' => $rider?->full_name ?? auth()->user()->name]) }}
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                            {{ __('rider.dashboard_intro') }}
                        </p>
                    </div>

                    <section class="rounded-lg border border-slate-200 bg-white p-6 text-slate-950 shadow-sm sm:p-8">
                        <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-sm font-bold uppercase text-teal-700">{{ __('rider.account_summary') }}</p>
                                <h2 class="mt-1 text-3xl font-black text-slate-950">
                                    {{ $rider ? __('rider.basic_profile_ready') : __('rider.profile_incomplete') }}
                                </h2>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('rider.profile.edit') }}" class="inline-flex w-fit justify-center rounded-md border border-slate-200 px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                                    {{ __('rider.edit_profile') }}
                                </a>
                                <a href="{{ route('rider.garage') }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                                    {{ __('rider.garage') }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.motorcycles') }}</p>
                                <p class="mt-2 text-3xl font-black text-slate-950">{{ $motorcycles->count() }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.documents') }}</p>
                                <p class="mt-2 text-3xl font-black text-slate-950">{{ $documentsCount }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.optional_delivery') }}</p>
                                <span class="mt-3 inline-flex rounded-full px-4 py-2 text-sm font-black {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.next_step') }}</p>
                                <p class="mt-2 text-base font-bold text-slate-950">
                                    {{ $nextStepLabel }}
                                </p>
                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    {{ $nextStepHelp }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-5 text-slate-950">
                            <p class="text-sm font-black uppercase text-amber-700">{{ __('rider.work_opportunities_optional') }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-700">
                                @if ($driverApplication)
                                    {{ __('rider.delivery_status_current', ['status' => $statusLabel]) }}
                                @elseif ($motorcycles->isEmpty())
                                    {{ __('rider.delivery_not_required') }}
                                @else
                                    {{ __('rider.delivery_optional_ready') }}
                                @endif
                            </p>
                            @if ($driverApplication)
                                <a href="{{ route('drivers.signup.create') }}" class="mt-5 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                                    {{ __('rider.view_delivery_application') }}
                                </a>
                            @elseif ($motorcycles->isEmpty())
                                <a href="{{ route('rider.garage') }}" class="mt-5 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                                    {{ __('rider.open_garage') }}
                                </a>
                            @else
                                <a href="{{ route('drivers.signup.create') }}" class="mt-5 inline-flex justify-center rounded-md border border-amber-300 bg-white px-5 py-3 text-sm font-black text-amber-800 transition hover:bg-amber-100">
                                    {{ __('rider.delivery_application_optional') }}
                                </a>
                            @endif
                        </div>
                    </section>
                </div>
            </section>
        </main>
    </body>
</html>
