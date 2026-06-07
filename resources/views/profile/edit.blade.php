@extends('layouts.app')

@section('content')
    @php
        $subscriptionIsActive = $subscription?->isActive() ?? false;
        $remainingSlots = max(0, $profilePublishedAdsLimit - $profilePublishedAdsCount);
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-[2rem] border border-slate-900/10 bg-white/90 p-6 shadow-sm backdrop-blur sm:p-8">
            <p class="text-sm font-black uppercase tracking-[0.2em] text-teal-700">{{ __('app.account_settings') }}</p>
            <h1 class="mt-2 text-4xl font-black text-slate-950">{{ __('app.profile_heading') }}</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">{{ __('app.profile_settings_intro') }}</p>
        </section>

        <div class="mt-6 grid gap-6 lg:grid-cols-[0.38fr_1fr]">
            <aside class="space-y-4">
                <section class="rounded-[1.5rem] border border-slate-200 bg-slate-950 p-5 text-white shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-yellow-300">{{ __('app.account_snapshot') }}</p>
                    <h2 class="mt-3 text-2xl font-black">{{ $user->name }}</h2>
                    <p class="mt-1 text-sm text-slate-300" dir="ltr">{{ $user->email }}</p>

                    <div class="mt-5 grid gap-3">
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-xs font-black uppercase text-slate-300">{{ __('app.subscription_status') }}</p>
                            <p class="mt-1 text-lg font-black">{{ $subscriptionIsActive ? $subscription->planLabel() : __('app.locked') }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-xs font-black uppercase text-slate-300">{{ __('app.active_ads_usage') }}</p>
                            <p class="mt-1 text-lg font-black">{{ $profilePublishedAdsCount }}/{{ $profilePublishedAdsLimit ?: 0 }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-black text-slate-950">{{ __('app.subscription_and_ads') }}</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        @if ($subscriptionIsActive)
                            {{ __('app.available_slots', ['count' => $remainingSlots]) }}
                        @else
                            {{ __('app.profile_subscription_help') }}
                        @endif
                    </p>
                    <a href="{{ route('subscriptions.show') }}" class="mt-4 inline-flex rounded-full bg-slate-950 px-4 py-2 text-sm font-black text-white">{{ __('app.manage_subscription') }}</a>
                </section>
            </aside>

            <div class="space-y-5">
                <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    @include('profile.partials.update-profile-information-form')
                </section>

                <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    @include('profile.partials.update-password-form')
                </section>

                <section class="rounded-[1.5rem] border border-rose-200 bg-rose-50 p-5 shadow-sm sm:p-7">
                    @include('profile.partials.delete-user-form')
                </section>
            </div>
        </div>
    </div>
@endsection
