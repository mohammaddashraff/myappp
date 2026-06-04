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
                                <p class="text-xs font-black uppercase text-slate-500">Active orders</p>
                                <p class="mt-2 text-3xl font-black text-slate-950">{{ $recentOrdersCount }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">Service bookings</p>
                                <p class="mt-2 text-3xl font-black text-slate-950">{{ $activeServiceBookingsCount }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">Roadside requests</p>
                                <p class="mt-2 text-3xl font-black text-slate-950">{{ $activeRoadsideRequestsCount }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">Battery requests</p>
                                <p class="mt-2 text-3xl font-black text-slate-950">{{ $activeBatteryRequestsCount }}</p>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-black uppercase text-slate-500">Saved addresses</p>
                                <p class="mt-2 text-3xl font-black text-slate-950">{{ $savedAddressesCount }}</p>
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

                        <div class="mt-6 rounded-lg border border-slate-200 bg-white p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-black uppercase text-teal-700">Rider activity</p>
                                    <h2 class="mt-1 text-xl font-black text-slate-950">Recent marketplace orders</h2>
                                </div>
                                <a href="{{ route('rider.orders.index') }}" class="inline-flex rounded-md border border-slate-200 px-4 py-2 text-sm font-black text-slate-700">
                                    View orders
                                </a>
                            </div>
                            <div class="mt-4 divide-y divide-slate-100">
                                @forelse ($recentOrders as $order)
                                    <a href="{{ route('rider.orders.show', $order) }}" class="flex items-center justify-between gap-4 py-3 text-sm">
                                        <span class="font-bold text-slate-950">{{ $order->order_number }}</span>
                                        <span class="text-slate-500">{{ $order->items->count() }} items · {{ $order->statusLabel() }}</span>
                                    </a>
                                @empty
                                    <p class="py-3 text-sm font-bold text-slate-500">No orders yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('rider.marketplace') }}" class="inline-flex rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Marketplace</a>
                            <a href="{{ route('rider.services.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Services</a>
                            <a href="{{ route('rider.roadside.create') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Roadside</a>
                            <a href="{{ route('rider.batteries.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Battery</a>
                            <a href="{{ route('rider.dealers.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Dealerships</a>
                            <a href="{{ route('rider.garage') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Garage</a>
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

                        <div class="mt-4 rounded-lg border border-teal-200 bg-teal-50 p-5 text-slate-950">
                            <p class="text-sm font-black uppercase text-teal-700">Provider applications</p>
                            <p class="mt-3 text-sm leading-6 text-slate-700">
                                Apply to become a seller, service center, roadside provider, delivery partner, or dealership. Admin approval is required before any provider dashboard opens.
                            </p>
                            <a href="{{ route('rider.provider-applications.index') }}" class="mt-5 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                                Apply as provider
                            </a>
                        </div>
                    </section>
                </div>
            </section>
        </main>
    </body>
</html>
