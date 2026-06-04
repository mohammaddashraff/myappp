@extends('riders.marketplace.layout')

@section('title', 'Rider Marketplace')
@section('active', 'marketplace')

@section('content')
    @php
        $cards = [
            ['name' => 'Accessories', 'description' => 'Helmets, gloves, phone holders, covers, locks, and riding gear.', 'route' => route('rider.products.accessories'), 'icon' => 'A'],
            ['name' => 'Spare Parts', 'description' => 'Tires, brakes, chains, oils, lights, mirrors, and engine parts.', 'route' => route('rider.products.spare-parts'), 'icon' => 'P'],
            ['name' => 'Services', 'description' => 'Book maintenance, oil change, tire change, inspection, and repair.', 'route' => route('rider.services.index'), 'icon' => 'S'],
            ['name' => 'Batteries', 'description' => 'Find compatible batteries and request replacement.', 'route' => route('rider.batteries.index'), 'icon' => 'B'],
            ['name' => 'Dealers / Showrooms', 'description' => 'Browse motorcycle dealers and send inquiries.', 'route' => route('rider.dealers.index'), 'icon' => 'D'],
        ];

        if ($canUseRiderActions) {
            $cards = array_merge($cards, [
                ['name' => 'Roadside Assistance', 'description' => 'Request towing, emergency support, fuel delivery, or flat tire help.', 'route' => route('rider.roadside.create'), 'icon' => 'R'],
                ['name' => 'My Orders', 'description' => $orderCount.' tracked product orders.', 'route' => route('rider.orders.index'), 'icon' => 'O'],
                ['name' => 'My Bookings', 'description' => $bookingCount.' service bookings.', 'route' => route('rider.bookings.index'), 'icon' => 'K'],
                ['name' => 'My Requests', 'description' => $requestCount.' assistance, battery, or dealer requests.', 'route' => route('rider.requests.index'), 'icon' => 'Q'],
            ]);
        }
    @endphp

    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">Rider marketplace</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950 sm:text-4xl">Marketplace and services</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                    Browse products, book workshops, request assistance, and track every rider-facing order or request from one place.
                </p>
            </div>
            @if ($canUseRiderActions)
                <a href="{{ route('rider.cart.index') }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                    Cart · {{ $cartCount }}
                </a>
            @else
                <span class="inline-flex w-fit rounded-md border border-teal-200 bg-teal-50 px-5 py-3 text-sm font-black text-teal-800">
                    Admin monitoring
                </span>
            @endif
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-black uppercase text-slate-500">Active products</p>
                <p class="mt-2 text-3xl font-black">{{ $productCount }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-black uppercase text-slate-500">Bookable services</p>
                <p class="mt-2 text-3xl font-black">{{ $serviceCount }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-black uppercase text-slate-500">Tracking items</p>
                <p class="mt-2 text-3xl font-black">{{ $orderCount + $bookingCount + $requestCount }}</p>
            </div>
        </div>
    </section>

    <section class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($cards as $card)
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start gap-4">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-yellow-300 text-lg font-black text-slate-950">{{ $card['icon'] }}</span>
                    <div>
                        <h2 class="text-xl font-black text-slate-950">{{ $card['name'] }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $card['description'] }}</p>
                    </div>
                </div>
                <a href="{{ $card['route'] }}" class="mt-5 inline-flex w-full justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white transition hover:bg-slate-800">
                    Open section
                </a>
            </article>
        @endforeach
    </section>
@endsection
