@extends('layouts.app')

@section('title', __('app.home_page').' | '.__('app.brand'))

@section('content')
    @php
        $subscriptionIsActive = $subscription?->isActive() ?? false;
        $slotPercentage = $publishedAdsLimit > 0 ? min(100, ($publishedAdsCount / $publishedAdsLimit) * 100) : 0;
        $remainingSlots = max(0, $publishedAdsLimit - $publishedAdsCount);
    @endphp

    <div class="dashboard-shell mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="frame-panel rounded-[1.5rem] border-teal-200 bg-teal-50 px-5 py-4 text-sm font-bold text-teal-900">
                {{ session('status') }}
            </div>
        @endif

        <section class="frame-panel-strong mt-6 overflow-hidden">
            <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1.3fr_0.7fr]">
                <div>
                    <p class="text-sm font-bold text-slate-300">{{ __('app.home_page') }}</p>
                    <h1 class="mt-3 max-w-2xl text-3xl font-black leading-tight sm:text-4xl">
                        {{ __('app.welcome_name', ['name' => auth()->user()->name]) }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                        {{ __('app.home_dashboard_intro') }}
                    </p>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ $subscriptionIsActive ? route('ads.create') : route('subscriptions.show') }}" class="inline-flex rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-white/15">
                            {{ $subscriptionIsActive ? __('app.post_ad') : __('app.pay_to_unlock') }}
                        </a>
                        <a href="{{ route('ads.my') }}" class="inline-flex rounded-full border border-white/20 px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-white/10">
                            {{ __('app.my_ads') }}
                        </a>
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-3">
                        <div class="dashboard-stat-dark">
                            <p class="text-xs font-bold text-slate-300">{{ __('app.published_ads_count') }}</p>
                            <p class="mt-2 text-3xl font-black">{{ $publishedAdsCount }}</p>
                        </div>
                        <div class="dashboard-stat-dark">
                            <p class="text-xs font-bold text-slate-300">{{ __('app.ad_limit') }}</p>
                            <p class="mt-2 text-3xl font-black">{{ $publishedAdsLimit ?: 0 }}</p>
                        </div>
                        <div class="dashboard-stat-dark">
                            <p class="text-xs font-bold text-slate-300">{{ __('app.slot_usage') }}</p>
                            <p class="mt-2 text-3xl font-black">{{ $remainingSlots }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.75rem] border border-white/10 bg-white/10 p-5 backdrop-blur">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-slate-300">{{ __('app.subscription_status') }}</p>
                            <p class="mt-2 text-2xl font-black">{{ $subscriptionIsActive ? $subscription->planLabel() : __('app.inactive') }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-black {{ $subscriptionIsActive ? 'bg-emerald-300 text-emerald-950' : 'bg-amber-300 text-amber-950' }}">
                            {{ $subscriptionIsActive ? __('app.active') : __('app.locked') }}
                        </span>
                    </div>

                    <div class="mt-5">
                        <div class="flex items-center justify-between text-sm font-bold text-slate-300">
                            <span>{{ __('app.slot_usage') }}</span>
                            <span>{{ $publishedAdsCount }}/{{ $publishedAdsLimit ?: 0 }}</span>
                        </div>
                        <div class="mt-3 h-3 overflow-hidden rounded-full bg-white/15">
                            <div class="h-full rounded-full bg-yellow-300" style="width: {{ $slotPercentage }}%"></div>
                        </div>
                        <p class="mt-3 text-sm text-slate-200">
                            @if ($subscriptionIsActive)
                                {{ __('app.available_slots', ['count' => $remainingSlots]) }}
                            @else
                                {{ __('app.locked_until_subscription') }}
                            @endif
                        </p>
                    </div>

                    <div class="mt-5 rounded-[1.4rem] border border-white/10 bg-slate-950/20 p-4">
                        <p class="text-sm font-bold text-slate-200">{{ __('app.quick_actions') }}</p>
                        <div class="mt-3 grid gap-2">
                            <a href="{{ $subscriptionIsActive ? route('ads.create') : route('subscriptions.show') }}" class="flex items-center justify-between rounded-[1rem] bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/15">
                                <span>{{ $subscriptionIsActive ? __('app.post_ad') : __('app.pay_to_unlock') }}</span>
                                <span class="text-slate-300">01</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-[1rem] bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/15">
                                <span>{{ __('app.profile') }}</span>
                                <span class="text-slate-300">02</span>
                            </a>
                            <a href="{{ route('ads.my') }}" class="flex items-center justify-between rounded-[1rem] bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/15">
                                <span>{{ __('app.my_ads') }}</span>
                                <span class="text-slate-300">03</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="dashboard-stat">
                <p class="dashboard-card-title">{{ __('app.subscription') }}</p>
                <p class="mt-2 text-2xl font-black text-slate-950">{{ $subscriptionIsActive ? $subscription->planLabel() : __('app.locked') }}</p>
            </div>
            <div class="dashboard-stat">
                <p class="dashboard-card-title">{{ __('app.published_ads_count') }}</p>
                <p class="mt-2 text-2xl font-black text-slate-950">{{ $publishedAdsCount }}</p>
            </div>
            <div class="dashboard-stat">
                <p class="dashboard-card-title">{{ __('app.ad_limit') }}</p>
                <p class="mt-2 text-2xl font-black text-slate-950">{{ $publishedAdsLimit ?: 0 }}</p>
            </div>
            <div class="dashboard-stat">
                <p class="dashboard-card-title">{{ __('app.available_slots', ['count' => $remainingSlots]) }}</p>
                <p class="mt-2 text-2xl font-black text-slate-950">{{ $remainingSlots }}</p>
            </div>
        </section>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
            <section class="frame-panel p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="section-label">{{ __('app.latest_ads') }}</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-950">{{ __('app.recent_activity') }}</h2>
                    </div>
                    <a href="{{ route('ads.my') }}" class="button-muted px-4 py-2">{{ __('app.view_all') }}</a>
                </div>

                <div class="mt-5 divide-y divide-slate-100">
                    @forelse ($recentAds as $ad)
                        <a href="{{ route('ads.show', $ad) }}" class="flex items-center justify-between gap-4 py-4 text-sm transition hover:px-2">
                            <span>
                                <span class="block font-black text-slate-950">{{ $ad->title }}</span>
                                <span class="mt-1 block text-slate-500">{{ $ad->categoryLabel() }} · {{ $ad->statusLabel() }}</span>
                            </span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-600">{{ $ad->created_at->diffForHumans() }}</span>
                        </a>
                    @empty
                        <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-stone-50 p-6 text-center">
                            <p class="text-sm font-black text-slate-600">{{ __('app.no_ads_yet') }}</p>
                            <a href="{{ route('subscriptions.show') }}" class="button-brand mt-3 px-4 py-2">{{ __('app.open_subscription') }}</a>
                        </div>
                    @endforelse
                </div>
            </section>

            <div class="grid gap-6">
                <section class="frame-panel p-6">
                    <p class="section-label">{{ __('app.quick_actions') }}</p>
                    <div class="mt-5 grid gap-3">
                        @foreach ([
                            ['href' => route('subscriptions.show'), 'title' => __('app.publish_after_payment'), 'body' => __('app.publish_after_payment_body')],
                            ['href' => route('ads.my'), 'title' => __('app.manage_my_ads'), 'body' => __('app.manage_my_ads_body')],
                            ['href' => route('profile.edit'), 'title' => __('app.account_contact_identity'), 'body' => __('app.profile_settings_intro')],
                        ] as $action)
                            <a href="{{ $action['href'] }}" class="group rounded-lg border border-slate-200 bg-stone-50 p-4 transition hover:-translate-y-0.5 hover:border-yellow-300 hover:bg-yellow-50">
                                <p class="font-black text-slate-950">{{ $action['title'] }}</p>
                                <p class="mt-1 text-sm leading-6 text-slate-600">{{ $action['body'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="frame-panel p-6">
                    <p class="section-label">{{ __('app.subscription_status') }}</p>
                    <div class="mt-4 rounded-lg bg-stone-50 p-5">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-lg font-black text-slate-950">{{ $subscriptionIsActive ? $subscription->planLabel() : __('app.inactive') }}</p>
                            <span class="rounded-full px-3 py-1 text-xs font-black {{ $subscriptionIsActive ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $subscriptionIsActive ? __('app.active') : __('app.locked') }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            @if ($subscriptionIsActive)
                                {{ __('app.available_slots', ['count' => $remainingSlots]) }}
                            @else
                                {{ __('app.locked_until_subscription') }}
                            @endif
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
