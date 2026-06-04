@extends('riders.marketplace.layout')

@section('title', 'Checkout')
@section('active', 'checkout')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">Checkout</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">Confirm your order</h1>
        <p class="mt-3 text-sm leading-6 text-slate-600">Choose delivery or pickup, then select one of the MVP payment methods.</p>
    </section>

    <form method="POST" action="{{ route('rider.checkout.store') }}" class="mt-5 grid gap-5 lg:grid-cols-[minmax(0,1fr)_340px]">
        @csrf
        <section class="grid gap-5">
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-black text-slate-950">Delivery method</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <label class="rounded-lg border border-slate-200 p-4">
                        <input type="radio" name="delivery_method" value="delivery" @checked(old('delivery_method', 'delivery') === 'delivery') class="text-teal-600">
                        <span class="ms-2 text-sm font-black">Delivery</span>
                    </label>
                    <label class="rounded-lg border border-slate-200 p-4">
                        <input type="radio" name="delivery_method" value="pickup" @checked(old('delivery_method') === 'pickup') class="text-teal-600">
                        <span class="ms-2 text-sm font-black">Pickup from seller/store</span>
                    </label>
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-black text-slate-950">Delivery address</h2>
                <div class="mt-4 grid gap-3">
                    @if ($savedAddresses->isNotEmpty())
                        <label class="rounded-lg border border-slate-200 p-4">
                            <input type="radio" name="address_choice" value="saved" @checked(old('address_choice', 'saved') === 'saved') class="text-teal-600">
                            <span class="ms-2 text-sm font-black">Use saved address</span>
                            <select name="saved_address_id" class="mt-3 block w-full rounded-md border-slate-300 text-sm">
                                @foreach ($savedAddresses as $savedAddress)
                                    <option value="{{ $savedAddress->id }}" @selected((int) old('saved_address_id', $savedAddresses->firstWhere('is_default', true)?->id ?? $savedAddresses->first()->id) === $savedAddress->id)>
                                        {{ $savedAddress->label }} - {{ $savedAddress->formattedAddress() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('saved_address_id')" class="mt-2" />
                        </label>
                    @elseif ($profileAddress)
                        <label class="rounded-lg border border-slate-200 p-4">
                            <input type="radio" name="address_choice" value="profile" @checked(old('address_choice', 'profile') === 'profile') class="text-teal-600">
                            <span class="ms-2 text-sm font-black">Use profile address</span>
                            <span class="mt-2 block text-sm text-slate-500">{{ $profileAddress }}</span>
                        </label>
                    @endif
                    <div class="rounded-lg border border-slate-200 p-4">
                        <input type="radio" name="address_choice" value="new" @checked(old('address_choice') === 'new' || ($savedAddresses->isEmpty() && ! $profileAddress)) class="text-teal-600">
                        <span class="ms-2 text-sm font-black">Enter new address</span>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <label class="grid gap-1 text-sm font-bold text-slate-700">
                                City
                                <input type="text" name="address_city" value="{{ old('address_city') }}" class="rounded-md border-slate-300 text-sm" placeholder="Cairo">
                            </label>
                            <label class="grid gap-1 text-sm font-bold text-slate-700">
                                Area / district
                                <input type="text" name="address_area" value="{{ old('address_area') }}" class="rounded-md border-slate-300 text-sm" placeholder="Nasr City">
                            </label>
                            <label class="grid gap-1 text-sm font-bold text-slate-700 sm:col-span-2">
                                Street name
                                <input type="text" name="address_street" value="{{ old('address_street') }}" class="rounded-md border-slate-300 text-sm" placeholder="Main street">
                            </label>
                            <label class="grid gap-1 text-sm font-bold text-slate-700">
                                Building number
                                <input type="text" name="address_building" value="{{ old('address_building') }}" class="rounded-md border-slate-300 text-sm" placeholder="12B">
                            </label>
                            <label class="grid gap-1 text-sm font-bold text-slate-700">
                                Floor
                                <input type="text" name="address_floor" value="{{ old('address_floor') }}" class="rounded-md border-slate-300 text-sm" placeholder="5">
                            </label>
                            <label class="grid gap-1 text-sm font-bold text-slate-700">
                                Apartment number
                                <input type="text" name="address_apartment" value="{{ old('address_apartment') }}" class="rounded-md border-slate-300 text-sm" placeholder="502">
                            </label>
                            <label class="grid gap-1 text-sm font-bold text-slate-700">
                                Landmark
                                <input type="text" name="address_landmark" value="{{ old('address_landmark') }}" class="rounded-md border-slate-300 text-sm" placeholder="Near fuel station">
                            </label>
                            <label class="grid gap-1 text-sm font-bold text-slate-700 sm:col-span-2">
                                Delivery notes
                                <input type="text" name="address_notes" value="{{ old('address_notes') }}" class="rounded-md border-slate-300 text-sm" placeholder="Call when arriving">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-black text-slate-950">Payment method</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <label class="rounded-lg border border-slate-200 p-4">
                        <input type="radio" name="payment_method" value="cash_on_delivery" @checked(old('payment_method', 'cash_on_delivery') === 'cash_on_delivery') class="text-teal-600">
                        <span class="ms-2 text-sm font-black">Cash on delivery</span>
                    </label>
                    <label class="rounded-lg border border-slate-200 p-4">
                        <input type="radio" name="payment_method" value="pay_at_pickup" @checked(old('payment_method') === 'pay_at_pickup') class="text-teal-600">
                        <span class="ms-2 text-sm font-black">Pay at pickup</span>
                    </label>
                    <label class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-slate-400">
                        <input type="radio" disabled class="text-slate-300">
                        <span class="ms-2 text-sm font-black">Wallet · Coming Soon</span>
                    </label>
                    <label class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-slate-400">
                        <input type="radio" disabled class="text-slate-300">
                        <span class="ms-2 text-sm font-black">Card · Coming Soon</span>
                    </label>
                </div>
            </div>
        </section>

        <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm lg:self-start">
            <h2 class="text-xl font-black text-slate-950">Checkout summary</h2>
            <div class="mt-4 grid gap-3">
                @foreach ($cartItems as $cartItem)
                    <div class="flex justify-between gap-4 text-sm">
                        <span class="text-slate-600">{{ $cartItem->product->name }} × {{ $cartItem->quantity }}</span>
                        <span class="font-black">EGP {{ number_format($cartItem->total()) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 space-y-3 border-t border-slate-200 pt-4 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Product subtotal</span><span class="font-black">EGP {{ number_format($subtotal) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Delivery fee</span><span class="font-black">EGP {{ number_format($deliveryFee) }}</span></div>
                <div class="flex justify-between text-base"><span class="font-black">Total amount</span><span class="font-black">EGP {{ number_format($subtotal + $deliveryFee) }}</span></div>
                <p class="text-xs font-semibold text-slate-500">Pickup orders remove the delivery fee when confirmed.</p>
            </div>
            <button type="submit" class="mt-5 inline-flex w-full justify-center rounded-md bg-slate-950 px-4 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                Confirm Order
            </button>
        </aside>
    </form>
@endsection
