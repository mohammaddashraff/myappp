@extends('layouts.app')

@section('title', (($isUpgrade ?? false) ? __('app.upgrade_checkout') : __('app.test_checkout')).' | '.__('app.brand'))

@section('content')
    @php
        $amount = $subscription->plan === \App\Models\Subscription::PLAN_BUSINESS ? '199' : '9.99';
    @endphp

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-2xl border border-teal-200 bg-teal-50 px-5 py-4 text-sm font-bold text-teal-900 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        <section class="moto-section mt-6 p-6 sm:p-8">
            <p class="text-sm font-black text-teal-700">{{ __('app.test_gateway') }}</p>
            <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-4xl font-black text-slate-950">{{ ($isUpgrade ?? false) ? __('app.upgrade_checkout') : __('app.test_checkout') }}</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">{{ ($isUpgrade ?? false) ? __('app.test_upgrade_intro') : __('app.test_payment_intro') }}</p>
                </div>
                <span class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-black text-amber-900">{{ __('app.no_real_charge') }}</span>
            </div>
        </section>

        <div class="mt-6 grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
            <section class="rounded-xl border border-slate-200 bg-slate-950 p-6 text-white shadow-sm">
                <p class="text-xs font-black text-yellow-300">{{ __('app.payment_summary') }}</p>
                <h2 class="mt-3 text-3xl font-black">{{ $subscription->planLabel() }}</h2>
                <p class="mt-2 text-sm text-slate-300">{{ ($isUpgrade ?? false) ? __('app.selected_upgrade_activates') : __('app.selected_plan_renews') }}</p>

                <div class="mt-6 rounded-lg bg-white/10 p-5">
                    <p class="text-xs font-black uppercase text-slate-300">{{ __('app.amount') }}</p>
                    <p class="mt-1 text-4xl font-black">EGP {{ $amount }}</p>
                </div>

                <div class="mt-5 rounded-lg border border-white/10 bg-white/10 p-4 text-sm leading-6 text-slate-300">
                    {{ __('app.test_card_hint') }}
                </div>
            </section>

            <form method="POST" action="{{ route('subscriptions.pay') }}" class="moto-section p-6">
                @csrf

                <div>
                    <p class="text-sm font-black text-teal-700">{{ __('app.card_details') }}</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">{{ __('app.secure_test_checkout') }}</h2>
                </div>

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <x-input-label for="cardholder_name" :value="__('app.cardholder_name')" />
                        <x-text-input id="cardholder_name" name="cardholder_name" type="text" class="mt-1 block w-full rounded-xl" :value="old('cardholder_name', auth()->user()->name)" required />
                        <x-input-error :messages="$errors->get('cardholder_name')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="card_number" :value="__('app.card_number')" />
                        <x-text-input id="card_number" name="card_number" type="text" inputmode="numeric" class="mt-1 block w-full rounded-xl" :value="old('card_number', '4242424242424242')" required dir="ltr" />
                        <x-input-error :messages="$errors->get('card_number')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="expiry_month" :value="__('app.expiry_month')" />
                        <x-text-input id="expiry_month" name="expiry_month" type="text" inputmode="numeric" class="mt-1 block w-full rounded-xl" :value="old('expiry_month', '12')" required dir="ltr" />
                        <x-input-error :messages="$errors->get('expiry_month')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="expiry_year" :value="__('app.expiry_year')" />
                        <x-text-input id="expiry_year" name="expiry_year" type="text" inputmode="numeric" class="mt-1 block w-full rounded-xl" :value="old('expiry_year', '30')" required dir="ltr" />
                        <x-input-error :messages="$errors->get('expiry_year')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="cvv" :value="__('app.cvv')" />
                        <x-text-input id="cvv" name="cvv" type="text" inputmode="numeric" class="mt-1 block w-full rounded-xl" :value="old('cvv', '123')" required dir="ltr" />
                        <x-input-error :messages="$errors->get('cvv')" class="mt-2" />
                    </div>
                </div>

                <button type="submit" class="button-brand mt-6 w-full">{{ __('app.pay_now_test') }}</button>
            </form>
        </div>
    </div>
@endsection
