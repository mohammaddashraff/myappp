@php
    $user = Auth::user();
    $navDashboardRoute = $navDashboardRoute ?? 'dashboard';
    $navIsAdmin = $navIsAdmin ?? false;
    $navActiveAdsCount = $navActiveAdsCount ?? 0;
    $navSubscription = $navSubscription ?? null;
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 shadow-sm backdrop-blur dark:border-white/10 dark:bg-slate-950/90">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-[4.5rem] justify-between py-3">
            <div class="flex min-w-0 items-center gap-8">
                <a href="{{ route($navDashboardRoute) }}" class="inline-flex items-center gap-3">
                    <span class="flex size-11 items-center justify-center rounded-lg bg-slate-950 text-lg font-black text-yellow-300 shadow-sm ring-4 ring-yellow-300/30">ط</span>
                    <span class="hidden md:inline">
                        <span class="block text-sm font-black text-slate-950 dark:text-slate-100">{{ __('app.brand') }}</span>
                        <span class="block text-[0.72rem] font-bold text-slate-500 dark:text-slate-400">{{ __('app.brand_subtitle') }}</span>
                    </span>
                </a>

                <div class="hidden flex-wrap gap-2 sm:flex">
                    @if ($navIsAdmin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('app.admin') }}</x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">{{ __('app.users') }}</x-nav-link>
                        <x-nav-link :href="route('admin.subscriptions.index')" :active="request()->routeIs('admin.subscriptions.*')">{{ __('app.subscriptions') }}</x-nav-link>
                        <x-nav-link :href="route('ads.index')" :active="request()->routeIs('ads.*')">{{ __('app.browse_ads') }}</x-nav-link>
                    @else
                        <x-nav-link :href="route('ads.index')" :active="request()->routeIs('ads.index', 'ads.show')">{{ __('app.browse_ads') }}</x-nav-link>
                        <x-nav-link :href="route('ads.my')" :active="request()->routeIs('ads.my', 'ads.create', 'ads.edit')">{{ __('app.my_ads') }} @if ($navActiveAdsCount > 0)<span class="ms-1 rounded-full bg-teal-100 px-2 py-0.5 text-xs font-black text-teal-800">{{ $navActiveAdsCount }}</span>@endif</x-nav-link>
                        <x-nav-link :href="route('subscriptions.show')" :active="request()->routeIs('subscriptions.*')">
                            {{ __('app.subscription') }}
                            @if ($navSubscription?->isActive())
                                <span class="ms-1 text-xs font-black text-emerald-700">{{ $navSubscription->statusLabel() }}</span>
                            @endif
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden gap-3 sm:flex sm:items-center">
                @if (! $navIsAdmin)
                    <a href="{{ route('subscriptions.show') }}" class="inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-xs font-black transition {{ $navSubscription?->isActive() ? 'border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' : 'border-amber-200 bg-amber-50 text-amber-900 hover:bg-amber-100' }}">
                        <span class="size-2 rounded-full {{ $navSubscription?->isActive() ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        {{ $navSubscription?->isActive() ? $navSubscription->planLabel() : __('app.locked') }}
                    </a>
                @endif

                <x-theme-toggle />
                <x-language-switcher variant="dark" />

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-bold text-slate-600 transition hover:bg-slate-50 hover:text-slate-950 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">
                            <span class="max-w-36 truncate">{{ $user->name }}</span>
                            <span class="ms-1">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('app.profile') }}</x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('app.log_out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-950 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="space-y-2 px-4 pb-3 pt-2">
            @if ($navIsAdmin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('app.admin') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">{{ __('app.users') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.subscriptions.index')" :active="request()->routeIs('admin.subscriptions.*')">{{ __('app.subscriptions') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ads.index')" :active="request()->routeIs('ads.*')">{{ __('app.browse_ads') }}</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('ads.index')" :active="request()->routeIs('ads.index', 'ads.show')">{{ __('app.browse_ads') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ads.my')" :active="request()->routeIs('ads.my', 'ads.create', 'ads.edit')">{{ __('app.my_ads') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('subscriptions.show')" :active="request()->routeIs('subscriptions.*')">{{ __('app.subscription') }}</x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-slate-200 pb-4 pt-4 dark:border-white/10">
            <div class="px-4">
                <div class="text-base font-bold text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
                @if (! $navIsAdmin)
                    <div class="mt-3 inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-black {{ $navSubscription?->isActive() ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-amber-200 bg-amber-50 text-amber-900' }}">
                        <span class="size-2 rounded-full {{ $navSubscription?->isActive() ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        {{ $navSubscription?->isActive() ? $navSubscription->planLabel() : __('app.locked') }}
                    </div>
                @endif
                <div class="mt-3 flex gap-2">
                    <x-theme-toggle />
                    <x-language-switcher variant="dark" />
                </div>
            </div>

            <div class="mt-3 space-y-2 px-4">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('app.profile') }}</x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('app.log_out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
