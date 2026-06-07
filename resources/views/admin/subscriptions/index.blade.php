@extends('layouts.app')

@section('title', __('app.subscriptions').' | '.__('app.brand'))

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-2xl border border-teal-200 bg-teal-50 px-5 py-4 text-sm font-bold text-teal-900 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        <section class="moto-section mt-6 p-6 sm:p-8">
            <p class="text-sm font-black text-teal-700">{{ __('app.admin') }}</p>
            <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-4xl font-black text-slate-950">{{ __('app.subscriptions') }}</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">{{ __('app.subscriptions_admin_intro') }}</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="button-muted">{{ __('app.admin') }}</a>
            </div>
        </section>

        <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="moto-table-head">
                        <tr>
                            <th class="p-4">{{ __('app.user') }}</th>
                            <th class="p-4">{{ __('app.plan') }}</th>
                            <th class="p-4">{{ __('app.status') }}</th>
                            <th class="p-4">{{ __('app.period') }}</th>
                            <th class="p-4">{{ __('app.payment') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($subscriptions as $subscription)
                            <tr class="align-top transition hover:bg-slate-50">
                                <td class="p-4">
                                    <p class="font-black text-slate-950">{{ $subscription->user->name }}</p>
                                    <p class="mt-1 text-xs font-bold text-slate-500">{{ $subscription->user->email }}</p>
                                </td>
                                <td class="p-4 font-bold text-slate-700">{{ $subscription->planLabel() }}</td>
                                <td class="p-4">
                                    <span class="rounded-lg px-3 py-1 text-xs font-black {{ $subscription->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">{{ $subscription->statusLabel() }}</span>
                                </td>
                                <td class="p-4 text-slate-600">
                                    {{ $subscription->starts_at?->translatedFormat('M d, Y') ?? __('app.not_started') }}
                                    -
                                    {{ $subscription->ends_at?->translatedFormat('M d, Y') ?? __('app.open_period') }}
                                </td>
                                <td class="p-4">
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold text-slate-500">{{ __('app.payment_gateway') }}: {{ $subscription->payment_gateway === 'test_gateway' ? __('app.test_gateway') : ($subscription->payment_gateway ?? __('app.not_paid_yet')) }}</p>
                                        <p class="text-xs font-bold text-slate-500">{{ __('app.payment_reference') }}: {{ $subscription->payment_reference ?? __('app.not_paid_yet') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500">{{ __('app.no_subscriptions_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5">{{ $subscriptions->links() }}</div>
    </div>
@endsection
