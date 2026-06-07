@extends('layouts.app')

@section('title', $seller->name.' | '.__('app.seller_profile'))

@section('content')
    @php
        $averageRating = $seller->averageRating();
        $reviewsCount = $seller->reviewsCount();
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-2xl border border-teal-200 bg-teal-50 px-5 py-4 text-sm font-bold text-teal-900 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-bold text-rose-900 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="frame-panel-strong mt-6 overflow-hidden">
            <div class="grid gap-8 p-6 sm:p-8 lg:grid-cols-[1fr_0.45fr]">
                <div>
                    <p class="text-sm font-black text-yellow-300">{{ __('app.seller_profile') }}</p>
                    <h1 class="mt-4 text-4xl font-black leading-tight sm:text-5xl">{{ $seller->name }}</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">{{ __('app.seller_profile_intro') }}</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="rounded-lg bg-white/10 px-4 py-2 text-sm font-black text-white">
                            {{ $averageRating !== null ? number_format($averageRating, 1).' / 5' : __('app.no_reviews_yet') }}
                        </span>
                        <span class="rounded-lg bg-white/10 px-4 py-2 text-sm font-black text-white">{{ trans_choice('app.reviews_count', $reviewsCount, ['count' => $reviewsCount]) }}</span>
                        <span class="rounded-lg bg-white/10 px-4 py-2 text-sm font-black text-white">{{ __('app.seller_since', ['date' => $seller->created_at->translatedFormat('M Y')]) }}</span>
                    </div>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 p-5">
                    <p class="text-xs font-black text-slate-300">{{ __('app.seller_activity') }}</p>
                    <div class="mt-5 grid gap-3">
                        <div class="rounded-lg bg-white/10 p-4">
                            <p class="text-xs font-black uppercase text-slate-300">{{ __('app.current_ads') }}</p>
                            <p class="mt-1 text-3xl font-black">{{ $currentAds->count() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/10 p-4">
                            <p class="text-xs font-black uppercase text-slate-300">{{ __('app.past_ads') }}</p>
                            <p class="mt-1 text-3xl font-black">{{ $pastAds->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_0.42fr]">
            <div class="space-y-6">
                <section class="moto-section p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-sm font-black text-teal-700">{{ __('app.current_ads') }}</p>
                            <h2 class="mt-1 text-2xl font-black text-slate-950">{{ __('app.current_ads_title') }}</h2>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        @forelse ($currentAds as $ad)
                            <article class="select-none overflow-hidden rounded-lg border border-slate-200 bg-slate-50" oncopy="return false" oncut="return false" oncontextmenu="return false">
                                <a href="{{ route('ads.show', $ad) }}" class="block">
                                    <div class="aspect-[4/3] bg-slate-100">
                                        @if ($ad->firstImageUrl() !== null)
                                            <img src="{{ $ad->firstImageUrl() }}" alt="{{ $ad->title }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full items-center justify-center text-sm font-black text-slate-400">{{ __('app.no_image') }}</div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <p class="text-lg font-black text-slate-950">{{ $ad->title }}</p>
                                        @if ($viewerCanSeeSellerContact)
                                            <p class="mt-2 text-sm font-bold text-slate-600">EGP {{ number_format((float) $ad->price) }} · {{ $ad->location }}</p>
                                        @else
                                            <p class="mt-2 text-sm font-bold text-amber-800">{{ __('app.details_hidden_until_subscribe') }}</p>
                                        @endif
                                    </div>
                                </a>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm font-bold text-slate-500 md:col-span-2">
                                {{ __('app.current_ads_empty') }}
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="moto-section p-6">
                    <p class="text-sm font-black text-teal-700">{{ __('app.past_ads') }}</p>
                    <h2 class="mt-1 text-2xl font-black text-slate-950">{{ __('app.past_ads_title') }}</h2>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        @forelse ($pastAds as $ad)
                            <article class="select-none overflow-hidden rounded-lg border border-slate-200 bg-slate-50 opacity-90" oncopy="return false" oncut="return false" oncontextmenu="return false">
                                <div class="block">
                                    <div class="aspect-[4/3] bg-slate-100">
                                        @if ($ad->firstImageUrl() !== null)
                                            <img src="{{ $ad->firstImageUrl() }}" alt="{{ $ad->title }}" class="h-full w-full object-cover grayscale-[0.2]">
                                        @else
                                            <div class="flex h-full items-center justify-center text-sm font-black text-slate-400">{{ __('app.no_image') }}</div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="text-lg font-black text-slate-950">{{ $ad->title }}</p>
                                            <span class="rounded-lg bg-slate-950 px-3 py-1 text-xs font-black text-white">{{ __('app.sold') }}</span>
                                        </div>
                                        <p class="mt-2 text-sm font-bold text-slate-500">{{ __('app.sold_on', ['date' => $ad->sold_at?->translatedFormat('M d, Y') ?? __('app.none')]) }}</p>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm font-bold text-slate-500 md:col-span-2">
                                {{ __('app.past_ads_empty') }}
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                @if (! auth()->user()->is($seller))
                    <form method="POST" action="{{ route('sellers.reviews.store', $seller) }}" class="moto-section p-6">
                        @csrf
                        <p class="text-sm font-black text-teal-700">{{ __('app.rate_this_user') }}</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-950">{{ __('app.your_review') }}</h2>

                        <div class="mt-5">
                            <x-input-label for="rating" :value="__('app.rating')" />
                            <select id="rating" name="rating" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                                @foreach (range(5, 1) as $rating)
                                    <option value="{{ $rating }}" @selected((int) old('rating', $viewerReview?->rating ?? 5) === $rating)>{{ trans_choice('app.stars', $rating, ['count' => $rating]) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="comment" :value="__('app.comment')" />
                            <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" placeholder="{{ __('app.review_comment_placeholder') }}">{{ old('comment', $viewerReview?->comment) }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>

                        <button type="submit" class="button-brand mt-5 w-full">
                            {{ $viewerReview ? __('app.update_review') : __('app.submit_review') }}
                        </button>
                    </form>
                @endif

                <section class="moto-section p-6">
                    <p class="text-sm font-black text-teal-700">{{ __('app.customer_reviews') }}</p>
                    <h2 class="mt-1 text-2xl font-black text-slate-950">{{ __('app.review_history') }}</h2>

                    <div class="mt-5 divide-y divide-slate-100 rounded-2xl border border-slate-200 bg-slate-50">
                        @forelse ($seller->receivedReviews as $review)
                            <div class="p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="font-black text-slate-950">{{ $review->reviewer->name }}</p>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-black text-slate-700">{{ $review->rating }} / 5</span>
                                </div>
                                @if ($review->comment)
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="p-4 text-sm font-bold text-slate-500">{{ __('app.no_review_comments_yet') }}</p>
                        @endforelse
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
