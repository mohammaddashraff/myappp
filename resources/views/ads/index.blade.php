@extends('layouts.app')

@section('title', $heading.' | '.__('app.brand'))

@section('content')
    @php
        $navIsAdmin = $navIsAdmin ?? false;
        $filterRoute = $showOwnerActions ? route('ads.my') : route('ads.index');
        $canSeeAdDetails = $showOwnerActions || $viewerCanSeeSellerContact;
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-2xl border border-teal-200 bg-teal-50 px-5 py-4 text-sm font-bold text-teal-900 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        <section class="mt-6 overflow-hidden rounded-xl border border-slate-900/10 bg-slate-950 text-white shadow-xl shadow-slate-900/10">
            <div class="grid gap-8 p-6 sm:p-8 lg:grid-cols-[1fr_0.82fr]">
                <div>
                    <p class="text-sm font-black text-yellow-300">{{ __('app.classifieds') }}</p>
                    <h1 class="mt-3 max-w-3xl text-4xl font-black leading-tight sm:text-5xl">{{ $heading }}</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">
                        {{ $canSeeAdDetails ? __('app.subscriber_mode_body') : __('app.public_browse_mode_body') }}
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="rounded-lg bg-white/10 px-4 py-2 text-sm font-black text-white">{{ __('app.motorcycle') }}</span>
                        <span class="rounded-lg bg-white/10 px-4 py-2 text-sm font-black text-white">{{ __('app.part') }}</span>
                        <span class="rounded-lg bg-white/10 px-4 py-2 text-sm font-black text-white">{{ __('app.accessory') }}</span>
                    </div>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <form method="GET" action="{{ $filterRoute }}" class="grid gap-3">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.search_ads') }}" class="w-full rounded-lg border-white/10 bg-white px-4 py-3 text-sm font-bold text-slate-950 placeholder:text-slate-400 focus:border-yellow-300 focus:ring-yellow-300">
                        <div class="grid gap-3 sm:grid-cols-[1fr_auto]">
                            <select name="category" class="rounded-lg border-white/10 bg-white px-4 py-3 text-sm font-bold text-slate-950 focus:border-yellow-300 focus:ring-yellow-300">
                                <option value="">{{ __('app.all_categories') }}</option>
                                @foreach (\App\Models\Ad::categories() as $category)
                                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ __('app.'.$category) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-lg bg-yellow-300 px-6 py-3 text-sm font-black text-slate-950 transition hover:bg-yellow-200">{{ __('app.filter') }}</button>
                        </div>
                    </form>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @if (! $navIsAdmin)
                            <a href="{{ route('ads.create') }}" class="inline-flex justify-center rounded-lg bg-white px-5 py-3 text-sm font-black text-slate-950 transition hover:bg-slate-100">{{ __('app.post_ad') }}</a>
                        @endif
                        <a href="{{ route('subscriptions.show') }}" class="inline-flex justify-center rounded-lg border border-white/20 px-5 py-3 text-sm font-black text-white transition hover:bg-white/10">{{ __('app.manage_subscription') }}</a>
                    </div>
                </div>
            </div>

            @if (! $canSeeAdDetails)
                <div class="border-t border-white/10 bg-amber-300 px-6 py-4 text-sm font-black text-amber-950 sm:px-8 lg:px-10">
                    {{ __('app.locked_ad_list_notice') }}
                </div>
            @endif
        </section>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($ads as $ad)
                @php
                    $imageUrls = $ad->imageUrls();
                    $rating = $ad->user->averageRating();
                @endphp

                <article class="group select-none overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-yellow-300 hover:shadow-xl hover:shadow-slate-900/10" oncopy="return false" oncut="return false" oncontextmenu="return false">
                    <a href="{{ route('ads.show', $ad) }}" class="block">
                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                            @if (($imageUrls[0] ?? null) !== null)
                                <img src="{{ $imageUrls[0] }}" alt="{{ $ad->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center text-sm font-black text-slate-400">{{ __('app.no_image') }}</div>
                            @endif

                            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-slate-950/75 to-transparent"></div>

                            @if ($canSeeAdDetails)
                                <span class="absolute start-4 top-4 rounded-lg bg-white/95 px-3 py-1 text-xs font-black text-slate-800 shadow-sm">{{ $ad->categoryLabel() }}</span>
                                <span class="absolute bottom-4 start-4 rounded-lg bg-slate-950/90 px-4 py-2 text-lg font-black text-white shadow-sm">EGP {{ number_format((float) $ad->price) }}</span>
                            @else
                                <span class="absolute start-4 top-4 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-black text-amber-900 shadow-sm">{{ __('app.locked') }}</span>
                                <span class="absolute bottom-4 start-4 rounded-lg bg-white/95 px-4 py-2 text-sm font-black text-amber-900 shadow-sm">{{ __('app.details_hidden_until_subscribe') }}</span>
                            @endif

                            @if (count($imageUrls) > 1)
                                <span class="absolute end-4 top-4 rounded-lg bg-slate-950/80 px-3 py-1 text-xs font-black text-white shadow-sm">{{ __('app.image_count', ['count' => count($imageUrls)]) }}</span>
                            @endif
                        </div>
                    </a>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h2 class="truncate text-2xl font-black text-slate-950">{{ $ad->title }}</h2>
                                @if ($canSeeAdDetails)
                                    <a href="{{ route('sellers.show', $ad->user) }}" class="mt-3 inline-flex max-w-full items-center gap-2 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-black text-slate-700 transition hover:bg-yellow-50 hover:text-yellow-900">
                                        <span class="truncate">{{ __('app.listed_by', ['name' => $ad->user->name]) }}</span>
                                        <span class="shrink-0 rounded-full bg-white px-2 py-0.5">{{ $rating !== null ? number_format($rating, 1).' / 5' : __('app.no_reviews_yet') }}</span>
                                    </a>
                                @endif
                            </div>

                            @if ($showOwnerActions)
                                <span class="shrink-0 rounded-lg bg-slate-100 px-3 py-1 text-xs font-black text-slate-700">{{ $ad->statusLabel() }}</span>
                            @endif
                        </div>

                        @if ($canSeeAdDetails)
                            <p class="mt-4 text-sm leading-6 text-slate-600">{{ str($ad->description)->limit(120) }}</p>
                            <div class="mt-4 grid grid-cols-2 gap-2 text-xs font-black text-slate-700">
                                <span class="rounded-2xl bg-slate-50 px-3 py-2">{{ $ad->location }}</span>
                                <span class="rounded-2xl bg-slate-50 px-3 py-2">{{ $ad->conditionLabel() }}</span>
                            </div>
                        @else
                            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4">
                                <p class="text-sm font-black text-amber-950">{{ __('app.details_locked_title') }}</p>
                                <div class="mt-3 grid gap-2">
                                    @foreach ([__('app.price'), __('app.description'), __('app.location'), __('app.seller_contact')] as $field)
                                        <div class="flex items-center justify-between rounded-lg bg-white/70 px-3 py-2 text-sm font-bold text-amber-900">
                                            <span>{{ $field }}</span>
                                            <span>{{ __('app.locked') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('ads.show', $ad) }}" class="inline-flex rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-black text-white transition hover:bg-slate-800">{{ __('app.view_details') }}</a>
                            @if ($showOwnerActions)
                                <a href="{{ route('ads.edit', $ad) }}" class="inline-flex rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-black text-slate-800 transition hover:bg-slate-50">{{ __('app.edit') }}</a>
                            @elseif (! $viewerCanSeeSellerContact)
                                <a href="{{ route('subscriptions.show') }}" class="inline-flex rounded-lg border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-black text-amber-900 transition hover:bg-amber-100">{{ __('app.unlock_contact_details') }}</a>
                            @else
                                <a href="{{ route('sellers.show', $ad->user) }}" class="inline-flex rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-black text-slate-800 transition hover:bg-slate-50">{{ __('app.seller_profile') }}</a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white/80 p-10 text-center text-sm font-bold text-slate-500 md:col-span-2 xl:col-span-3">
                    {{ __('app.no_ads_found') }}
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $ads->links() }}</div>
    </div>
@endsection
