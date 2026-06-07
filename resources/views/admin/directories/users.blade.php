@extends('layouts.app')

@section('title', __('app.users').' | '.__('app.brand'))

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="moto-section p-6 sm:p-8">
            <p class="text-sm font-black text-teal-700">{{ __('app.admin') }}</p>
            <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-4xl font-black text-slate-950">{{ __('app.users') }}</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">{{ __('app.user_directory_intro') }}</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="button-muted">{{ __('app.admin') }}</a>
            </div>
        </section>

        <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="moto-table-head">
                        <tr>
                            <th class="p-4">{{ __('app.name') }}</th>
                            <th class="p-4">{{ __('app.roles') }}</th>
                            <th class="p-4">{{ __('app.subscription') }}</th>
                            <th class="p-4">{{ __('app.joined') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="align-top transition hover:bg-slate-50">
                                <td class="p-4">
                                    <p class="font-black text-slate-950">{{ $user->name }}</p>
                                    <p class="mt-1 text-xs font-bold text-slate-500">{{ $user->email }}</p>
                                </td>
                                <td class="p-4">
                                    <span class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-black text-slate-700">{{ $user->roles->pluck('name')->map(fn ($role) => str($role)->headline())->join(', ') ?: __('app.none') }}</span>
                                </td>
                                <td class="p-4">
                                    @if ($user->subscription)
                                        <span class="rounded-lg px-3 py-1 text-xs font-black {{ $user->subscription->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                            {{ $user->subscription->planLabel() }} · {{ $user->subscription->statusLabel() }}
                                        </span>
                                    @else
                                        <span class="rounded-lg bg-amber-100 px-3 py-1 text-xs font-black text-amber-900">{{ __('app.locked') }}</span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-600">{{ $user->created_at->translatedFormat('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-500">{{ __('app.no_users_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5">{{ $users->links() }}</div>
    </div>
@endsection
