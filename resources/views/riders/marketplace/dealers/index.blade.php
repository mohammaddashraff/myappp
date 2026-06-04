@extends('riders.marketplace.layout')

@section('title', 'Dealers / Showrooms')
@section('active', 'dealers')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">Inquiry flow</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">Dealers / Showrooms</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">Browse dealer inventory and send inquiries without any dealer dashboard.</p>

        <form method="GET" class="mt-6 grid gap-3 md:grid-cols-4">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Dealer or location" class="rounded-md border-slate-300 text-sm">
            <select name="brand" class="rounded-md border-slate-300 text-sm">
                <option value="">All motorcycle brands</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand }}" @selected(request('brand') === $brand)>{{ $brand }}</option>
                @endforeach
            </select>
            <select name="condition" class="rounded-md border-slate-300 text-sm">
                <option value="">Any condition</option>
                <option value="new" @selected(request('condition') === 'new')>New</option>
                <option value="used" @selected(request('condition') === 'used')>Used</option>
            </select>
            <button type="submit" class="rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Filter</button>
        </form>
    </section>

    <section class="mt-5">
        <h2 class="text-xl font-black text-slate-950">Showrooms</h2>
        @if ($dealers->isEmpty())
            <div class="mt-3 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
                <h3 class="text-lg font-black">No dealers found</h3>
            </div>
        @else
            <div class="mt-3 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($dealers as $dealer)
                    <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-xl font-black text-slate-950">{{ $dealer->name }}</h3>
                                <p class="mt-1 text-sm font-bold text-slate-500">{{ $dealer->location }}</p>
                            </div>
                            @if ($dealer->rating)
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-black text-yellow-800">{{ $dealer->rating }}/5</span>
                            @endif
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Brands: {{ collect($dealer->brands_available)->join(', ') ?: 'Not specified' }}</p>
                        <p class="mt-1 text-sm text-slate-500">Phone: {{ $dealer->phone ?? 'Phone placeholder' }}</p>
                        <div class="mt-5 grid gap-2 sm:grid-cols-2">
                            <a href="{{ route('rider.dealers.show', $dealer) }}" class="inline-flex justify-center rounded-md border border-slate-200 px-4 py-2.5 text-sm font-black text-slate-700">View Motorcycles</a>
                            @if ($canUseRiderActions)
                                <a href="{{ route('rider.dealers.inquiries.create', $dealer) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Send Inquiry</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="mt-6">
        <h2 class="text-xl font-black text-slate-950">Motorcycles</h2>
        @if ($motorcycles->isEmpty())
            <div class="mt-3 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
                <h3 class="text-lg font-black">No motorcycles found</h3>
            </div>
        @else
            <div class="mt-3 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($motorcycles as $motorcycle)
                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                        @if ($motorcycle->image)
                            <img src="{{ $motorcycle->image }}" alt="{{ $motorcycle->fullName() }}" class="aspect-[4/3] w-full object-cover">
                        @else
                            <div class="flex aspect-[4/3] w-full items-center justify-center bg-slate-100 text-sm font-black text-slate-400">Motorcycle image</div>
                        @endif
                        <div class="p-5">
                            <p class="text-xs font-black uppercase text-teal-700">{{ $motorcycle->condition }}</p>
                            <h3 class="mt-1 text-xl font-black">{{ $motorcycle->brand }} {{ $motorcycle->model }}</h3>
                            <p class="mt-1 text-sm font-bold text-slate-500">{{ $motorcycle->year }} · {{ $motorcycle->engine_cc }} CC</p>
                            <p class="mt-3 text-2xl font-black">EGP {{ number_format((float) $motorcycle->price) }}</p>
                            <p class="mt-2 text-sm text-slate-600">{{ $motorcycle->installment_available ? 'Installment available' : 'Installment not available' }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $motorcycle->dealer->name }} · {{ $motorcycle->dealer->location }}</p>
                            <div class="mt-5 grid gap-2 sm:grid-cols-2">
                                <a href="{{ route('rider.dealer-motorcycles.show', $motorcycle) }}" class="inline-flex justify-center rounded-md border border-slate-200 px-4 py-2.5 text-sm font-black text-slate-700">View Details</a>
                                @if ($canUseRiderActions)
                                    <a href="{{ route('rider.dealer-motorcycles.inquiries.create', [$motorcycle->dealer, $motorcycle]) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Send Inquiry</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-6">{{ $motorcycles->links() }}</div>
        @endif
    </section>
@endsection
