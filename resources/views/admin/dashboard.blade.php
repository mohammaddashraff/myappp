@extends('layouts.app')

@section('title', __('app.admin').' | '.__('app.brand'))

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="frame-panel-strong overflow-hidden">
            <div class="p-6 sm:p-8">
                <p class="text-sm font-black text-yellow-300">{{ __('app.admin') }}</p>
                <div class="mt-4 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h1 class="max-w-3xl text-4xl font-black leading-tight sm:text-5xl">{{ __('app.admin_command_center') }}</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">{{ __('app.manage_subscriptions_monitor_ads') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex rounded-lg bg-yellow-300 px-5 py-3 text-sm font-black text-slate-950 transition hover:bg-yellow-200">{{ __('app.manage_subscriptions') }}</a>
                        <a href="{{ route('ads.index') }}" class="inline-flex rounded-lg border border-white/20 px-5 py-3 text-sm font-black text-white transition hover:bg-white/10">{{ __('app.browse_ads') }}</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                ['label' => __('app.total_users'), 'value' => $totalUsers, 'tone' => 'bg-white'],
                ['label' => __('app.active_subscriptions'), 'value' => $activeSubscriptions, 'tone' => 'bg-emerald-50'],
                ['label' => __('app.inactive_subscriptions'), 'value' => $inactiveSubscriptions, 'tone' => 'bg-amber-50'],
                ['label' => __('app.published_ads'), 'value' => $publishedAds, 'tone' => 'bg-teal-50'],
                ['label' => __('app.draft_ads'), 'value' => $draftAds, 'tone' => 'bg-slate-50'],
                ['label' => __('app.sold_ads'), 'value' => $soldAds, 'tone' => 'bg-yellow-50'],
            ] as $metric)
                <div class="rounded-xl border border-slate-200 {{ $metric['tone'] }} p-5 shadow-sm">
                    <p class="text-sm font-black text-slate-500">{{ $metric['label'] }}</p>
                    <p class="mt-3 text-4xl font-black text-slate-950">{{ number_format($metric['value']) }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_0.8fr]">
            <section class="moto-section p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-black text-teal-700">{{ __('app.subscription_pipeline') }}</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-950">{{ __('app.recent_subscription_requests') }}</h2>
                    </div>
                    <a href="{{ route('admin.subscriptions.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 transition hover:bg-slate-50">{{ __('app.view_all') }}</a>
                </div>

                <div class="mt-5 divide-y divide-slate-100">
                    @forelse ($recentSubscriptions as $subscription)
                        <div class="flex flex-col gap-3 py-4 text-sm sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-black text-slate-950">{{ $subscription->user->name }}</p>
                                <p class="mt-1 text-slate-500">{{ $subscription->planLabel() }} · {{ $subscription->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="w-fit rounded-lg px-3 py-1 text-xs font-black {{ $subscription->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">{{ $subscription->statusLabel() }}</span>
                        </div>
                    @empty
                        <p class="py-4 text-sm font-bold text-slate-500">{{ __('app.no_subscriptions_yet') }}</p>
                    @endforelse
                </div>
            </section>

            <section class="moto-section p-6">
                <p class="text-sm font-black text-teal-700">{{ __('app.admin_scope') }}</p>
                <h2 class="mt-2 text-2xl font-black text-slate-950">{{ __('app.subscription_revenue_test_mode') }}</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">{{ __('app.admin_test_gateway_body') }}</p>
                <div class="mt-5 grid gap-3">
                    <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm font-black text-slate-800 transition hover:bg-yellow-50">{{ __('app.users') }}</a>
                    <a href="{{ route('admin.subscriptions.index') }}" class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm font-black text-slate-800 transition hover:bg-yellow-50">{{ __('app.subscriptions') }}</a>
                </div>
            </section>
        </div>
    </div>
@endsection
