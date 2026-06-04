@extends('riders.marketplace.layout')

@section('title', 'Cart')
@section('active', 'cart')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">Cart</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Selected products</h1>
            </div>
            <a href="{{ route('rider.checkout.show') }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                Proceed to checkout
            </a>
        </div>
    </section>

    @if ($cartItems->isEmpty())
        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-xl font-black text-slate-950">Your cart is empty</h2>
            <p class="mt-2 text-sm text-slate-500">Add accessories, spare parts, or batteries to start an order.</p>
            <a href="{{ route('rider.marketplace') }}" class="mt-5 inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Open Marketplace</a>
        </section>
    @else
        <section class="mt-5 grid gap-5 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="grid gap-4">
                @foreach ($cartItems as $cartItem)
                    @php($cartProductImageUrl = $cartItem->product->imageUrl())
                    <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="grid gap-4 sm:grid-cols-[120px_minmax(0,1fr)]">
                            @if ($cartProductImageUrl)
                                <img src="{{ $cartProductImageUrl }}" alt="{{ $cartItem->product->name }}" class="aspect-square w-full rounded-lg object-cover">
                            @else
                                <div class="flex aspect-square w-full items-center justify-center rounded-lg bg-slate-100 text-xs font-black text-slate-400">Image</div>
                            @endif
                            <div>
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-xs font-black uppercase text-teal-700">{{ $cartItem->product->typeLabel() }}</p>
                                        <h2 class="mt-1 text-xl font-black text-slate-950">{{ $cartItem->product->name }}</h2>
                                        <p class="mt-1 text-sm font-bold text-slate-500">EGP {{ number_format((float) $cartItem->product->price) }} each</p>
                                    </div>
                                    <p class="text-lg font-black text-slate-950">EGP {{ number_format($cartItem->total()) }}</p>
                                </div>
                                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <form
                                        method="POST"
                                        action="{{ route('rider.cart.update', $cartItem) }}"
                                        class="flex items-center gap-2"
                                        x-data="{
                                            quantity: {{ $cartItem->quantity }},
                                            min: 1,
                                            max: {{ $cartItem->product->stock_quantity }},
                                            submit() {
                                                this.quantity = Math.min(this.max, Math.max(this.min, Number(this.quantity) || this.min));
                                                this.$nextTick(() => this.$refs.form.requestSubmit());
                                            },
                                        }"
                                        x-ref="form"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" aria-label="Decrease quantity" @click="if (quantity > min) { quantity--; submit(); }" :disabled="quantity <= min" class="flex size-10 items-center justify-center rounded-md border border-slate-200 bg-white text-lg font-black text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:text-slate-300">
                                            -
                                        </button>
                                        <input type="number" name="quantity" min="1" max="{{ $cartItem->product->stock_quantity }}" x-model.number="quantity" @change="submit()" class="w-20 rounded-md border-slate-300 text-center text-sm font-black">
                                        <button type="button" aria-label="Increase quantity" @click="if (quantity < max) { quantity++; submit(); }" :disabled="quantity >= max" class="flex size-10 items-center justify-center rounded-md border border-slate-200 bg-white text-lg font-black text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:text-slate-300">
                                            +
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('rider.cart.destroy', $cartItem) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-rose-200 px-4 py-2 text-sm font-black text-rose-700">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm lg:self-start">
                @php($deliveryFee = \App\Http\Controllers\RiderCheckoutController::DELIVERY_FEE)
                <h2 class="text-xl font-black text-slate-950">Summary</h2>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span class="font-black">EGP {{ number_format($subtotal) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Delivery fee</span><span class="font-black">EGP {{ number_format($deliveryFee) }}</span></div>
                    <div class="border-t border-slate-200 pt-3">
                        <div class="flex justify-between text-base"><span class="font-black">Total with delivery</span><span class="font-black">EGP {{ number_format($subtotal + $deliveryFee) }}</span></div>
                    </div>
                </div>
                <a href="{{ route('rider.checkout.show') }}" class="mt-5 inline-flex w-full justify-center rounded-md bg-slate-950 px-4 py-3 text-sm font-black text-white">Proceed to checkout</a>
            </aside>
        </section>
    @endif
@endsection
