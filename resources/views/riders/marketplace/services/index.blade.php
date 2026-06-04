@extends('riders.marketplace.layout')

@section('title', 'Services')
@section('active', 'services')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">Booking flow</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Services / Workshops</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">Book maintenance, oil change, tire change, inspection, repair, wash, or emergency service.</p>
            </div>
            @if ($canUseRiderActions)
                <a href="{{ route('rider.bookings.index') }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">My Bookings</a>
            @else
                <span class="inline-flex w-fit rounded-md border border-teal-200 bg-teal-50 px-5 py-3 text-sm font-black text-teal-800">Admin monitoring</span>
            @endif
        </div>

        <form method="GET" class="mt-6 grid gap-3 lg:grid-cols-6">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Service name" class="rounded-md border-slate-300 text-sm">
            <select name="category" class="rounded-md border-slate-300 text-sm">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
            <select name="location" class="rounded-md border-slate-300 text-sm">
                <option value="">All locations</option>
                @foreach ($locations as $location)
                    <option value="{{ $location }}" @selected(request('location') === $location)>{{ $location }}</option>
                @endforeach
            </select>
            <input type="number" name="min_price" value="{{ request('min_price') }}" min="0" placeholder="Min price" class="rounded-md border-slate-300 text-sm">
            <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" placeholder="Max price" class="rounded-md border-slate-300 text-sm">
            <input type="number" step="0.1" name="rating" value="{{ request('rating') }}" min="0" max="5" placeholder="Min rating" class="rounded-md border-slate-300 text-sm">
            <input type="text" name="motorcycle_type" value="{{ request('motorcycle_type') }}" placeholder="Motorcycle type" class="rounded-md border-slate-300 text-sm">
            <label class="flex items-center gap-2 rounded-md border border-slate-200 px-3 py-2 text-sm font-bold text-slate-700">
                <input type="checkbox" name="available_today" value="1" @checked(request()->boolean('available_today')) class="rounded border-slate-300 text-teal-600">
                Available today
            </label>
            <button type="submit" class="rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Filter</button>
            <a href="{{ route('rider.services.index') }}" class="inline-flex justify-center rounded-md border border-slate-200 px-4 py-2.5 text-sm font-black text-slate-700">Reset</a>
        </form>
    </section>

    @if ($services->isEmpty())
        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-xl font-black text-slate-950">No services available</h2>
            <p class="mt-2 text-sm text-slate-500">Try another category or location.</p>
        </section>
    @else
        <section class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($services as $service)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase text-teal-700">{{ $service->category }}</p>
                            <h2 class="mt-1 text-xl font-black text-slate-950">{{ $service->name }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $service->description }}</p>
                        </div>
                        @if ($service->rating)
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-black text-yellow-800">{{ $service->rating }}/5</span>
                        @endif
                    </div>
                    <dl class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
                        <div class="rounded-md bg-slate-50 px-3 py-2"><dt class="text-xs font-black uppercase text-slate-400">Workshop</dt><dd class="font-bold">{{ $service->service_center_name }}</dd></div>
                        <div class="rounded-md bg-slate-50 px-3 py-2"><dt class="text-xs font-black uppercase text-slate-400">Location</dt><dd class="font-bold">{{ $service->location }}</dd></div>
                        <div class="rounded-md bg-slate-50 px-3 py-2"><dt class="text-xs font-black uppercase text-slate-400">Price</dt><dd class="font-bold">EGP {{ number_format((float) $service->estimated_price) }}</dd></div>
                        <div class="rounded-md bg-slate-50 px-3 py-2"><dt class="text-xs font-black uppercase text-slate-400">Duration</dt><dd class="font-bold">{{ $service->estimated_duration }}</dd></div>
                    </dl>
                    <div class="mt-5 grid gap-2 sm:grid-cols-2">
                        <a href="{{ route('rider.services.show', $service) }}" class="inline-flex justify-center rounded-md border border-slate-200 px-4 py-2.5 text-sm font-black text-slate-700">View Details</a>
                        @if ($canUseRiderActions)
                            <a href="{{ route('rider.bookings.create', $service) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Book Service</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>

        <div class="mt-6">{{ $services->links() }}</div>
    @endif
@endsection
