@extends('layouts.app')

@section('title', $ad->title.' | '.__('app.brand'))

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-2xl border border-teal-200 bg-teal-50 px-5 py-4 text-sm font-bold text-teal-900 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        <section class="moto-section mt-6 select-none overflow-hidden" oncopy="return false" oncut="return false" oncontextmenu="return false">
            <div class="grid lg:grid-cols-[0.95fr_1.05fr]">
                <div class="bg-slate-950/5 p-5 sm:p-6">
                    <div class="aspect-[4/3] overflow-hidden rounded-lg bg-slate-100 shadow-inner">
                        @if ($ad->firstImageUrl() !== null)
                            <img src="{{ $ad->firstImageUrl() }}" alt="{{ $ad->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-sm font-black text-slate-400">{{ __('app.no_image') }}</div>
                        @endif
                    </div>

                    @if (count($ad->imageUrls()) > 1)
                        <div class="mt-4 grid grid-cols-4 gap-3">
                            @foreach (array_slice($ad->imageUrls(), 1, 4) as $imageUrl)
                                <div class="aspect-square overflow-hidden rounded-2xl bg-slate-100">
                                    <img src="{{ $imageUrl }}" alt="{{ $ad->title }}" class="h-full w-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (! $canSeeContact)
                        <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-bold leading-6 text-amber-900">
                            {{ __('app.locked_ad_show_notice') }}
                        </div>
                    @endif
                </div>

                <div class="p-6 sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-black text-teal-700">{{ __('app.classified_ad') }}</p>
                            <h1 class="mt-2 text-4xl font-black leading-tight text-slate-950">{{ $ad->title }}</h1>
                            <a href="{{ route('sellers.show', $ad->user) }}" class="mt-4 inline-flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-black text-slate-700 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-900">
                                {{ __('app.seller') }}: {{ $ad->user->name }}
                                <span class="rounded-full bg-white px-2 py-0.5 text-xs">{{ $ad->user->averageRating() !== null ? number_format($ad->user->averageRating(), 1).' / 5' : __('app.no_reviews_yet') }}</span>
                            </a>
                        </div>

                        @if ($canSeeContact)
                            <p class="rounded-lg bg-slate-950 px-4 py-3 text-2xl font-black text-white">EGP {{ number_format((float) $ad->price) }}</p>
                        @else
                            <span class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-black text-amber-900">{{ __('app.details_locked_short') }}</span>
                        @endif
                    </div>

                    @if ($canSeeContact)
                        <p class="mt-5 text-sm leading-7 text-slate-600">{{ $ad->description }}</p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            @foreach ([
                                __('app.category') => $ad->categoryLabel(),
                                __('app.condition') => $ad->conditionLabel(),
                                __('app.location') => $ad->location,
                                __('app.status') => $ad->statusLabel(),
                            ] as $label => $value)
                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                                    <p class="text-xs font-black text-slate-500">{{ $label }}</p>
                                    <p class="mt-1 text-sm font-black text-slate-950">{{ $value }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 p-5">
                            <p class="text-sm font-black text-emerald-900">{{ __('app.seller_contact') }}</p>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-black uppercase text-emerald-700">{{ __('app.seller') }}</p>
                                    <p class="mt-1 text-lg font-black text-emerald-950">{{ $ad->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-black uppercase text-emerald-700">{{ __('app.phone') }}</p>
                                    @if ($phoneRevealed)
                                        <p class="mt-1 text-lg font-black text-emerald-950" dir="ltr">{{ $ad->contact_phone }}</p>
                                    @else
                                        <form method="POST" action="{{ route('ads.reveal-phone', $ad) }}" class="mt-2">
                                            @csrf
                                            <button type="submit" class="inline-flex rounded-lg bg-emerald-700 px-4 py-2 text-sm font-black text-white transition hover:bg-emerald-800">
                                                {{ __('app.show_phone_number') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-5">
                            <p class="text-sm font-black text-amber-900">{{ __('app.details_locked_title') }}</p>
                            <p class="mt-2 text-sm leading-6 text-amber-800">{{ __('app.details_locked_body') }}</p>
                            <div class="mt-5 grid gap-2 sm:grid-cols-2">
                                @foreach ([__('app.price'), __('app.description'), __('app.category'), __('app.condition'), __('app.location'), __('app.seller_contact')] as $field)
                                    <div class="flex items-center justify-between rounded-lg bg-white/70 px-3 py-2 text-sm font-bold text-amber-900">
                                        <span>{{ $field }}</span>
                                        <span>{{ __('app.locked') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 flex flex-wrap gap-3">
                        @if ($canManageAd)
                            <a href="{{ route('ads.edit', $ad) }}" class="inline-flex rounded-lg bg-slate-950 px-5 py-3 text-sm font-black text-white">{{ __('app.edit') }}</a>

                            @if (! $ad->isSold())
                                <form method="POST" action="{{ route('ads.mark-sold', $ad) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">{{ __('app.mark_as_sold') }}</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('subscriptions.show') }}" class="inline-flex rounded-lg bg-slate-950 px-5 py-3 text-sm font-black text-white">
                                {{ $canSeeContact ? __('app.manage_subscription') : __('app.unlock_contact_details') }}
                            </a>
                            <a href="{{ route('ads.index') }}" class="inline-flex rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">{{ __('app.browse_ads') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @if ($canManageAd && $adAnalytics !== null)
            <section class="moto-section mt-6 p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-black text-teal-700">{{ __('app.ad_analytics') }}</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-950">{{ __('app.viewer_activity') }}</h2>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-lg bg-slate-950 p-5 text-white">
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-300">{{ __('app.ad_views') }}</p>
                        <p class="mt-2 text-4xl font-black">{{ $adAnalytics['views'] }}</p>
                    </div>
                    <div class="rounded-lg bg-emerald-50 p-5 text-emerald-950">
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-emerald-700">{{ __('app.phone_reveal_clicks') }}</p>
                        <p class="mt-2 text-4xl font-black">{{ $adAnalytics['phone_reveals'] }}</p>
                    </div>
                </div>

                <p class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm font-bold leading-6 text-slate-600">
                    {{ __('app.analytics_privacy_note') }}
                </p>
            </section>
        @endif
    </div>
@endsection
