@extends('layouts.app')

@section('title', __('app.subscription').' | '.__('app.brand'))

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-2xl border border-teal-200 bg-teal-50 px-5 py-4 text-sm font-bold text-teal-900 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        <section class="frame-panel-strong mt-6 overflow-hidden">
            <div class="grid gap-8 p-6 sm:p-8 lg:grid-cols-[1fr_0.75fr]">
                <div>
                    <p class="text-sm font-black text-yellow-300">{{ __('app.subscription') }}</p>
                    <h1 class="mt-4 max-w-3xl text-4xl font-black leading-tight sm:text-5xl">{{ __('app.choose_your_access') }}</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">{{ __('app.subscription_value_intro') }}</p>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 p-5">
                    <p class="text-xs font-black text-slate-300">{{ __('app.current_subscription') }}</p>
                    @if ($subscription)
                        <p class="mt-2 text-3xl font-black">{{ $subscription->planLabel() }}</p>
                        <p class="mt-2 inline-flex rounded-lg px-3 py-1 text-xs font-black {{ $subscription->isActive() ? 'bg-emerald-300 text-emerald-950' : 'bg-amber-300 text-amber-950' }}">{{ $subscription->statusLabel() }}</p>
                        <p class="mt-4 text-sm leading-6 text-slate-300">
                            @if ($subscription->isActive())
                                {{ __('app.subscription_active_allows', ['count' => $subscription->planLimit(), 'label' => str(__('app.ads'))->lower()]) }}
                            @else
                                {{ __('app.subscription_pending_payment') }}
                            @endif
                        </p>
                    @else
                        <p class="mt-2 text-3xl font-black">{{ __('app.no_active_subscription') }}</p>
                        <p class="mt-4 text-sm leading-6 text-slate-300">{{ __('app.browse_only_without_subscription') }}</p>
                    @endif
                </div>
            </div>
        </section>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
            @foreach ([
                [
                    'plan' => \App\Models\Subscription::PLAN_INDIVIDUAL,
                    'title' => __('app.individual'),
                    'limit' => 1,
                    'price' => '9.99',
                    'description' => __('app.plan_individual_description'),
                    'accent' => 'border-teal-200 bg-teal-50 text-teal-900',
                ],
                [
                    'plan' => \App\Models\Subscription::PLAN_BUSINESS,
                    'title' => __('app.business'),
                    'limit' => 5,
                    'price' => '199',
                    'description' => __('app.plan_business_description'),
                    'accent' => 'border-yellow-200 bg-yellow-50 text-yellow-950',
                ],
            ] as $plan)
                @php
                    $currentPlan = $subscription?->isActive() ? $subscription->plan : null;
                    $isCurrentPlan = $currentPlan === $plan['plan'];
                    $isBusinessActive = $currentPlan === \App\Models\Subscription::PLAN_BUSINESS;
                    $isUpgradePlan = $currentPlan === \App\Models\Subscription::PLAN_INDIVIDUAL && $plan['plan'] === \App\Models\Subscription::PLAN_BUSINESS;
                    $isDisabled = $isCurrentPlan || ($isBusinessActive && $plan['plan'] === \App\Models\Subscription::PLAN_INDIVIDUAL);
                    $buttonLabel = $isCurrentPlan
                        ? __('app.current_plan')
                        : ($isUpgradePlan
                            ? __('app.upgrade_to_business')
                            : (($isBusinessActive && $plan['plan'] === \App\Models\Subscription::PLAN_INDIVIDUAL)
                                ? __('app.best_plan_active')
                                : __('app.continue_to_payment')));
                @endphp
                <form method="POST" action="{{ route('subscriptions.store') }}" class="moto-section p-6 transition hover:-translate-y-1 hover:border-yellow-300 hover:shadow-xl hover:shadow-slate-900/10">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $plan['plan'] }}">

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-black text-teal-700">{{ $plan['title'] }}</p>
                            <h2 class="mt-2 text-4xl font-black text-slate-950">EGP {{ $plan['price'] }}</h2>
                            <p class="mt-1 text-sm font-bold text-slate-500">{{ __('app.monthly_test_price') }}</p>
                        </div>
                        <span class="rounded-lg border px-3 py-1 text-xs font-black {{ $plan['accent'] }}">{{ __('app.active_ads_label', ['count' => $plan['limit'], 'label' => __('app.ads')]) }}</span>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-slate-600">{{ $plan['description'] }}</p>

                    <div class="mt-5 grid gap-3">
                        @foreach ([__('app.contact_visibility_included'), __('app.publish_until_limit'), __('app.sold_ads_free_slots')] as $feature)
                            <div class="flex items-center gap-3 rounded-lg bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700">
                                <span class="flex size-6 items-center justify-center rounded-full bg-emerald-100 text-xs font-black text-emerald-700">+</span>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" @disabled($isDisabled) class="mt-6 inline-flex w-full justify-center rounded-lg px-5 py-3 text-sm font-black transition {{ $isDisabled ? 'cursor-not-allowed bg-slate-200 text-slate-500' : 'bg-slate-950 text-white hover:bg-slate-800' }}">{{ $buttonLabel }}</button>
                </form>
            @endforeach
        </div>
    </div>
@endsection
