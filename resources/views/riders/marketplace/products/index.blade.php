@extends('riders.marketplace.layout')

@section('title', $heading)
@section('active', $productType === \App\Models\Product::TYPE_ACCESSORY ? 'accessories' : 'spare-parts')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-950">{{ $heading }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Compare items by stock, delivery, pickup, location, brand, and motorcycle compatibility.
                </p>
            </div>
            @if ($canUseRiderActions)
                <a href="{{ route('rider.cart.index') }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-2.5 text-sm font-black text-white transition hover:bg-slate-800">
                    View Cart
                </a>
            @else
                <span class="inline-flex w-fit rounded-md border border-teal-200 bg-teal-50 px-5 py-2.5 text-sm font-black text-teal-800">
                    Admin monitoring
                </span>
            @endif
        </div>

        <form method="GET" class="mt-5 grid gap-3">
            <div class="grid gap-3 lg:grid-cols-[minmax(220px,1.4fr)_repeat(3,minmax(150px,1fr))_auto]">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $productType === \App\Models\Product::TYPE_SPARE_PART ? 'Part name' : 'Product name' }}" class="rounded-md border-slate-300 text-sm">
                <select name="category" class="rounded-md border-slate-300 text-sm">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                <select name="brand" class="rounded-md border-slate-300 text-sm">
                    <option value="">All brands</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand }}" @selected(request('brand') === $brand)>{{ $brand }}</option>
                    @endforeach
                </select>
                <select name="location" class="rounded-md border-slate-300 text-sm">
                    <option value="">All locations</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location }}" @selected(request('location') === $location)>{{ $location }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-md bg-slate-950 px-5 py-2.5 text-sm font-black text-white transition hover:bg-slate-800">Filter</button>
            </div>

            <div class="grid gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3 md:grid-cols-2 xl:grid-cols-4">
                <input type="number" name="min_price" value="{{ request('min_price') }}" min="0" placeholder="Min price" class="rounded-md border-slate-300 text-sm">
                <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" placeholder="Max price" class="rounded-md border-slate-300 text-sm">
                <select name="condition" class="rounded-md border-slate-300 text-sm">
                    <option value="">Any condition</option>
                    <option value="new" @selected(request('condition') === 'new')>New</option>
                    <option value="used" @selected(request('condition') === 'used')>Used</option>
                </select>
                <input type="text" name="motorcycle_type" value="{{ request('motorcycle_type') }}" placeholder="Motorcycle type" class="rounded-md border-slate-300 text-sm">
                <input type="text" name="motorcycle_brand" value="{{ request('motorcycle_brand') }}" placeholder="Motorcycle brand" class="rounded-md border-slate-300 text-sm">
                <input type="text" name="motorcycle_model" value="{{ request('motorcycle_model') }}" placeholder="Motorcycle model" class="rounded-md border-slate-300 text-sm">
                <a href="{{ url()->current() }}" class="inline-flex justify-center rounded-md border border-slate-200 bg-white px-4 py-2.5 text-sm font-black text-slate-700 transition hover:bg-slate-50">Reset</a>
                <div class="flex flex-wrap gap-3 md:col-span-2 xl:col-span-4">
                    <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700">
                        <input type="checkbox" name="delivery_available" value="1" @checked(request()->boolean('delivery_available')) class="rounded border-slate-300 text-teal-600">
                        Delivery
                    </label>
                    <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700">
                        <input type="checkbox" name="pickup_available" value="1" @checked(request()->boolean('pickup_available')) class="rounded border-slate-300 text-teal-600">
                        Pickup
                    </label>
                    <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700">
                        <input type="checkbox" name="in_stock" value="1" @checked(request()->boolean('in_stock')) class="rounded border-slate-300 text-teal-600">
                        In stock only
                    </label>
                </div>
            </div>
        </form>
    </section>

    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm font-bold text-slate-600">
            {{ $products->total() }} {{ str($heading)->lower() }} found
        </p>
    </div>

    @if ($products->isEmpty())
        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-xl font-black text-slate-950">No products found</h2>
            <p class="mt-2 text-sm text-slate-500">Try changing the filters or search term.</p>
        </section>
    @else
        <section class="mt-3 grid gap-3">
            @foreach ($products as $product)
                @include('riders.marketplace.partials.product-card', ['product' => $product])
            @endforeach
        </section>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
@endsection
