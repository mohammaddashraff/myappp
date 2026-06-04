@extends('riders.marketplace.layout')

@section('title', 'Batteries')
@section('active', 'batteries')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">Request flow</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Batteries</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">Buy a battery like a normal product or request installation/replacement.</p>
            </div>
            @if ($canUseRiderActions)
                <a href="{{ route('rider.cart.index') }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">View Cart</a>
            @else
                <span class="inline-flex w-fit rounded-md border border-teal-200 bg-teal-50 px-5 py-3 text-sm font-black text-teal-800">Admin monitoring</span>
            @endif
        </div>

        <form method="GET" class="mt-6 grid gap-3 md:grid-cols-4">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Battery or brand" class="rounded-md border-slate-300 text-sm">
            <select name="location" class="rounded-md border-slate-300 text-sm">
                <option value="">All locations</option>
                @foreach ($locations as $location)
                    <option value="{{ $location }}" @selected(request('location') === $location)>{{ $location }}</option>
                @endforeach
            </select>
            <label class="flex items-center gap-2 rounded-md border border-slate-200 px-3 py-2 text-sm font-bold text-slate-700">
                <input type="checkbox" name="installation_available" value="1" @checked(request()->boolean('installation_available')) class="rounded border-slate-300 text-teal-600">
                Installation available
            </label>
            <button type="submit" class="rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Filter</button>
        </form>
    </section>

    @if ($batteries->isEmpty())
        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-xl font-black text-slate-950">No products found</h2>
            <p class="mt-2 text-sm text-slate-500">Try another location or search term.</p>
        </section>
    @else
        <section class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($batteries as $battery)
                @php($batteryImageUrl = $battery->imageUrl())
                <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                    @if ($batteryImageUrl)
                        <img src="{{ $batteryImageUrl }}" alt="{{ $battery->name }}" class="aspect-[4/3] w-full object-cover">
                    @else
                        <div class="flex aspect-[4/3] w-full items-center justify-center bg-slate-100 text-sm font-black text-slate-400">Battery image</div>
                    @endif
                    <div class="p-5">
                        <p class="text-xs font-black uppercase text-teal-700">{{ $battery->brand }}</p>
                        <h2 class="mt-1 text-xl font-black text-slate-950">{{ $battery->name }}</h2>
                        <p class="mt-2 text-2xl font-black">EGP {{ number_format((float) $battery->price) }}</p>
                        <dl class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
                            <div class="rounded-md bg-slate-50 px-3 py-2"><dt class="text-xs font-black uppercase text-slate-400">Voltage</dt><dd class="font-bold">{{ $battery->voltage }}</dd></div>
                            <div class="rounded-md bg-slate-50 px-3 py-2"><dt class="text-xs font-black uppercase text-slate-400">Capacity</dt><dd class="font-bold">{{ $battery->capacity }}</dd></div>
                            <div class="rounded-md bg-slate-50 px-3 py-2"><dt class="text-xs font-black uppercase text-slate-400">Location</dt><dd class="font-bold">{{ $battery->location }}</dd></div>
                            <div class="rounded-md bg-slate-50 px-3 py-2"><dt class="text-xs font-black uppercase text-slate-400">Warranty</dt><dd class="font-bold">{{ $battery->warranty_info }}</dd></div>
                        </dl>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Compatible: {{ collect($battery->compatible_motorcycle_types)->join(', ') }} · {{ collect($battery->compatible_motorcycle_models)->join(', ') }}</p>
                        <div class="mt-4 flex flex-wrap gap-2 text-xs font-black">
                            <span class="rounded-full bg-teal-100 px-3 py-1 text-teal-800">Delivery {{ $battery->delivery_available ? 'yes' : 'no' }}</span>
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-yellow-800">Installation {{ $battery->installation_available ? 'yes' : 'no' }}</span>
                        </div>
                        @if ($canUseRiderActions)
                            <div class="mt-5 grid gap-2 sm:grid-cols-2">
                                <form method="POST" action="{{ route('rider.cart.store', $battery) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Add to Cart</button>
                                </form>
                                <a href="{{ route('rider.batteries.installation.create', $battery) }}" class="inline-flex justify-center rounded-md border border-slate-200 px-4 py-2.5 text-sm font-black text-slate-700">Request Installation</a>
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>
        <div class="mt-6">{{ $batteries->links() }}</div>
    @endif
@endsection
