@props(['product'])

<article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:border-slate-300 hover:shadow-md">
    @php
        $visual = match ($product->type) {
            \App\Models\Product::TYPE_ACCESSORY => ['bg' => '#ecfeff', 'fg' => '#0f766e', 'ring' => '#99f6e4'],
            \App\Models\Product::TYPE_SPARE_PART => ['bg' => '#eff6ff', 'fg' => '#1d4ed8', 'ring' => '#bfdbfe'],
            \App\Models\Product::TYPE_BATTERY => ['bg' => '#fefce8', 'fg' => '#a16207', 'ring' => '#fde68a'],
            default => ['bg' => '#f8fafc', 'fg' => '#334155', 'ring' => '#e2e8f0'],
        };
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true);
        $productImageUrl = $product->imageUrl();
    @endphp

    <div class="grid lg:grid-cols-[178px_minmax(0,1fr)_220px]">
        <div class="bg-slate-100 p-3">
            @if ($productImageUrl)
                <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" class="aspect-[4/3] w-full rounded-md object-cover lg:aspect-square">
            @else
                <div class="flex aspect-[4/3] w-full flex-col items-center justify-center rounded-md border text-center lg:aspect-square" style="background: {{ $visual['bg'] }}; border-color: {{ $visual['ring'] }}; color: {{ $visual['fg'] }};">
                    <svg viewBox="0 0 64 64" aria-hidden="true" class="h-14 w-14">
                        <path d="M18 42h28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4" />
                        <path d="M22 42l8-18h12l6 18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" />
                        <path d="M26 24h20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4" />
                        <circle cx="20" cy="44" r="7" fill="none" stroke="currentColor" stroke-width="4" />
                        <circle cx="46" cy="44" r="7" fill="none" stroke="currentColor" stroke-width="4" />
                    </svg>
                    <span class="mt-3 text-xs font-black uppercase tracking-wide">{{ $product->category }}</span>
                </div>
            @endif
        </div>

        <div class="p-4 lg:p-5">
            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-black uppercase text-teal-700">{{ $product->category }}</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-600">{{ ucfirst($product->condition) }}</span>
                <span class="rounded-full {{ $product->isInStock() ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} px-3 py-1 text-xs font-black">
                    {{ $product->isInStock() ? 'In stock' : 'Out of stock' }}
                </span>
            </div>
            <h2 class="mt-3 text-xl font-black leading-tight text-slate-950">{{ $product->name }}</h2>
            <p class="mt-1 text-sm font-bold text-slate-500">{{ $product->brand }} · {{ $product->seller_name }}</p>
            <p class="mt-3 text-sm leading-6 text-slate-600">{{ str($product->description)->limit(150) }}</p>

            <div class="mt-4 grid gap-2 text-sm sm:grid-cols-3">
                <div>
                    <p class="text-xs font-black uppercase text-slate-400">Location</p>
                    <p class="mt-1 font-bold text-slate-700">{{ $product->location }}</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase text-slate-400">Stock</p>
                    <p class="mt-1 font-bold {{ $product->isInStock() ? 'text-emerald-700' : 'text-rose-700' }}">{{ $product->stock_quantity }} available</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase text-slate-400">Compatible</p>
                    <p class="mt-1 font-bold text-slate-700">{{ collect($product->compatible_motorcycle_types)->take(2)->join(', ') ?: 'Not specified' }}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 bg-slate-50 p-4 lg:border-s lg:border-t-0 lg:p-5">
            <p class="text-xs font-black uppercase text-slate-400">Price</p>
            <p class="mt-1 text-2xl font-black text-slate-950">EGP {{ number_format((float) $product->price) }}</p>
            <div class="mt-4 grid gap-2 text-xs font-black">
                <span class="rounded-md {{ $product->delivery_available ? 'bg-teal-100 text-teal-800' : 'bg-slate-200 text-slate-500' }} px-3 py-2">Delivery {{ $product->delivery_available ? 'available' : 'not available' }}</span>
                <span class="rounded-md {{ $product->pickup_available ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-500' }} px-3 py-2">Pickup {{ $product->pickup_available ? 'available' : 'not available' }}</span>
            </div>
            <div class="mt-5 grid gap-2">
                <a href="{{ route('rider.products.show', $product) }}" class="inline-flex justify-center rounded-md border border-slate-200 bg-white px-4 py-2.5 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                    View Details
                </a>
                @if ($canUseRiderActions ?? true)
                    @if ($isWishlisted)
                        <a href="{{ route('rider.profile.edit') }}#wishlist" class="inline-flex justify-center rounded-md border border-teal-200 bg-teal-50 px-4 py-2.5 text-sm font-black text-teal-800 transition hover:bg-teal-100">
                            Saved to Wishlist
                        </a>
                    @else
                        <form method="POST" action="{{ route('rider.wishlist.store', $product) }}">
                            @csrf
                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-black text-amber-800 transition hover:bg-amber-100">
                                Save to Wishlist
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('rider.cart.store', $product) }}">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" @disabled(! $product->isInStock()) class="inline-flex w-full justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:bg-slate-300">
                            Add to Cart
                        </button>
                    </form>
                @else
                    <span class="inline-flex justify-center rounded-md border border-slate-200 bg-white px-4 py-2.5 text-sm font-black text-slate-500">
                        Customer actions hidden
                    </span>
                @endif
            </div>
        </div>
    </div>
</article>
