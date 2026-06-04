@extends('riders.marketplace.layout')

@section('title', $product->name)
@section('active', $product->type === \App\Models\Product::TYPE_ACCESSORY ? 'accessories' : ($product->type === \App\Models\Product::TYPE_BATTERY ? 'batteries' : 'spare-parts'))

@section('content')
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true);
        $productImageUrl = $product->imageUrl();
        $relatedIndexHref = match ($product->type) {
            \App\Models\Product::TYPE_SPARE_PART => route('rider.products.spare-parts'),
            \App\Models\Product::TYPE_BATTERY => route('rider.batteries.index'),
            default => route('rider.products.accessories'),
        };
    @endphp

    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="grid xl:grid-cols-[minmax(0,0.95fr)_minmax(420px,1.05fr)]">
            <div class="border-b border-slate-200 bg-slate-50 p-4 sm:p-5 xl:border-b-0 xl:border-e">
                <div class="relative aspect-[16/11] w-full overflow-hidden rounded-lg bg-slate-100 xl:aspect-[5/4]">
                    @if ($productImageUrl)
                        <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                        <div class="hidden flex h-full w-full items-center justify-center text-sm font-black text-slate-400">Product image</div>
                    @else
                        <div class="flex h-full w-full items-center justify-center text-sm font-black text-slate-400">Product image</div>
                    @endif
                </div>
            </div>
            <div class="p-5 sm:p-6 xl:p-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase text-teal-700">{{ $product->typeLabel() }} · {{ $product->category }}</p>
                        <h1 class="mt-2 text-3xl font-black leading-tight text-slate-950">{{ $product->name }}</h1>
                    </div>
                    <p class="shrink-0 text-3xl font-black text-slate-950">EGP {{ number_format((float) $product->price) }}</p>
                </div>
                <p class="mt-4 text-sm leading-6 text-slate-600">{{ $product->description }}</p>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        'Brand' => $product->brand,
                        'Condition' => ucfirst($product->condition),
                        'Available quantity' => $product->stock_quantity,
                        'Seller/store' => $product->seller_name,
                        'Seller location' => $product->location,
                        'Delivery option' => $product->delivery_available ? 'Available' : 'Not available',
                        'Pickup option' => $product->pickup_available ? 'Available' : 'Not available',
                        'Estimated delivery' => $product->estimated_delivery_time ?? 'Not specified',
                        'Warranty' => $product->warranty_info ?? 'Not available',
                        'Return policy' => $product->return_policy ?? 'Not available',
                    ] as $label => $value)
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-black uppercase text-slate-500">{{ $label }}</p>
                            <p class="mt-1 text-sm font-bold text-slate-950">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

                @if ($product->voltage || $product->capacity)
                    <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                        <p class="text-sm font-black text-slate-950">Battery specs</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $product->voltage }} · {{ $product->capacity }}</p>
                    </div>
                @endif

                <div class="mt-4 rounded-lg border border-slate-200 bg-white p-4">
                    <p class="text-sm font-black text-slate-950">Compatible motorcycles</p>
                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        Types: {{ collect($product->compatible_motorcycle_types)->join(', ') ?: 'Not specified' }}<br>
                        Brands/models: {{ collect($product->compatible_motorcycle_brands)->join(', ') ?: 'Not specified' }} · {{ collect($product->compatible_motorcycle_models)->join(', ') ?: 'Not specified' }}
                    </p>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    @if ($canUseRiderActions)
                        <form method="POST" action="{{ route('rider.cart.store', $product) }}">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" @disabled(! $product->isInStock()) class="inline-flex w-full justify-center rounded-md bg-slate-950 px-4 py-3 text-sm font-black text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:bg-slate-300">Add to Cart</button>
                        </form>
                        <form method="POST" action="{{ route('rider.cart.store', $product) }}">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="checkout" value="1">
                            <button type="submit" @disabled(! $product->isInStock()) class="inline-flex w-full justify-center rounded-md bg-teal-600 px-4 py-3 text-sm font-black text-white transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:bg-slate-300">Buy Now</button>
                        </form>
                        @if ($isWishlisted)
                            <a href="{{ route('rider.profile.edit') }}#wishlist" class="inline-flex w-full justify-center rounded-md border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-black text-teal-800 transition hover:bg-teal-100">
                                Saved
                            </a>
                        @else
                            <form method="POST" action="{{ route('rider.wishlist.store', $product) }}">
                                @csrf
                                <button type="submit" class="inline-flex w-full justify-center rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-black text-amber-800 transition hover:bg-amber-100">
                                    Wishlist
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="rounded-lg border border-teal-200 bg-teal-50 p-4 sm:col-span-2">
                            <p class="text-sm font-black text-teal-900">Admin monitoring mode</p>
                            <p class="mt-1 text-sm text-teal-800">Customer actions such as cart, checkout, and wishlist are hidden for admin accounts.</p>
                        </div>
                    @endif
                    <button type="button" disabled class="inline-flex w-full cursor-not-allowed justify-center rounded-md border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-black text-slate-400">
                        Contact Seller · Coming Soon
                    </button>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-bold uppercase text-teal-700">Related</p>
                    <h2 class="mt-1 text-2xl font-black text-slate-950">More like this</h2>
                </div>
                <a href="{{ $relatedIndexHref }}" class="text-sm font-black text-teal-700">
                    View all
                </a>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($relatedProducts as $relatedProduct)
                    @php
                        $relatedIsWishlisted = in_array($relatedProduct->id, $wishlistProductIds ?? [], true);
                        $relatedProductImageUrl = $relatedProduct->imageUrl();
                    @endphp

                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:border-slate-300 hover:shadow-md">
                        <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
                            @if ($relatedProductImageUrl)
                                <img src="{{ $relatedProductImageUrl }}" alt="{{ $relatedProduct->name }}" class="h-full w-full object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                <div class="hidden flex h-full w-full items-center justify-center text-xs font-black text-slate-400">Product image</div>
                            @else
                                <div class="flex h-full w-full items-center justify-center text-xs font-black text-slate-400">Product image</div>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-black uppercase text-teal-700">{{ $relatedProduct->category }}</span>
                                <span class="rounded-full {{ $relatedProduct->isInStock() ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} px-3 py-1 text-xs font-black">
                                    {{ $relatedProduct->isInStock() ? 'In stock' : 'Out of stock' }}
                                </span>
                            </div>
                            <h3 class="mt-3 text-lg font-black leading-tight text-slate-950">{{ $relatedProduct->name }}</h3>
                            <p class="mt-1 text-sm font-bold text-slate-500">{{ $relatedProduct->brand }} · {{ $relatedProduct->location }}</p>
                            <p class="mt-3 text-2xl font-black text-slate-950">EGP {{ number_format((float) $relatedProduct->price) }}</p>

                            <div class="mt-4 grid gap-2">
                                <a href="{{ route('rider.products.show', $relatedProduct) }}" class="inline-flex justify-center rounded-md border border-slate-200 bg-white px-4 py-2.5 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                                    View Details
                                </a>
                                @if ($canUseRiderActions)
                                    @if ($relatedIsWishlisted)
                                        <a href="{{ route('rider.profile.edit') }}#wishlist" class="inline-flex justify-center rounded-md border border-teal-200 bg-teal-50 px-4 py-2.5 text-sm font-black text-teal-800 transition hover:bg-teal-100">
                                            Saved to Wishlist
                                        </a>
                                    @else
                                        <form method="POST" action="{{ route('rider.wishlist.store', $relatedProduct) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-black text-amber-800 transition hover:bg-amber-100">
                                                Save to Wishlist
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
