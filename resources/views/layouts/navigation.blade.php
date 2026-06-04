@php
    $user = Auth::user();
    $navCartCount = $navCartCount ?? 0;
    $navBusinessGroups = $navBusinessGroups ?? [];
    $navDashboardRoute = $navDashboardRoute ?? 'dashboard';
    $navProviderStatusItems = $navProviderStatusItems ?? [];
    $navIsAdmin = $navIsAdmin ?? false;
    $navShowsRiderGarage = $navDashboardRoute === 'rider.dashboard';
    $businessActive = request()->routeIs('seller.*', 'service-center.*', 'roadside-provider.*', 'delivery-partner.*', 'dealership.*');
@endphp

<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex min-w-0">
                <div class="flex shrink-0 items-center">
                    <a href="{{ route($navDashboardRoute) }}" class="inline-flex items-center gap-3">
                        <span class="flex size-10 items-center justify-center rounded-lg bg-yellow-300 text-lg font-black text-slate-950 shadow-sm">ط</span>
                        <span class="hidden text-sm font-black text-slate-950 md:inline">{{ __('rider.brand') }}</span>
                    </a>
                </div>

                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                    @if ($navIsAdmin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('rider.nav_admin_dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('rider.nav_users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.provider-applications.index')" :active="request()->routeIs('admin.provider-applications.*')">
                            {{ __('rider.nav_provider_applications') }}
                        </x-nav-link>
                        <x-nav-link :href="route('rider.marketplace')" :active="request()->routeIs('rider.marketplace', 'rider.products.*')">
                            {{ __('rider.nav_marketplace') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.sellers.index')" :active="request()->routeIs('admin.sellers.*')">
                            {{ __('rider.nav_sellers') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.service-centers.index')" :active="request()->routeIs('admin.service-centers.*')">
                            {{ __('rider.nav_service_centers') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.dealerships.index')" :active="request()->routeIs('admin.dealerships.*')">
                            {{ __('rider.nav_dealerships') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route($navDashboardRoute)" :active="request()->routeIs($navDashboardRoute)">
                            {{ __('rider.nav_dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('rider.marketplace')" :active="request()->routeIs('rider.marketplace', 'rider.products.*')">
                            {{ __('rider.nav_marketplace') }}
                        </x-nav-link>
                        <x-nav-link :href="route('rider.cart.index')" :active="request()->routeIs('rider.cart.*')">
                            {{ __('rider.nav_cart') }}@if ($navCartCount > 0)<span class="ms-1 rounded-full bg-teal-100 px-2 py-0.5 text-xs font-black text-teal-800">{{ $navCartCount }}</span>@endif
                        </x-nav-link>
                        <x-nav-link :href="route('rider.orders.index')" :active="request()->routeIs('rider.orders.*')">
                            {{ __('rider.nav_orders') }}
                        </x-nav-link>
                        @if ($navShowsRiderGarage)
                            <x-nav-link :href="route('rider.garage')" :active="request()->routeIs('rider.garage', 'rider.motorcycles.*')">
                                {{ __('rider.nav_garage') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('rider.services.index')" :active="request()->routeIs('rider.services.*', 'rider.bookings.*')">
                            {{ __('rider.nav_services') }}
                        </x-nav-link>
                        <x-nav-link :href="route('rider.dealers.index')" :active="request()->routeIs('rider.dealers.*', 'rider.dealer-motorcycles.*')">
                            {{ __('rider.nav_dealerships') }}
                        </x-nav-link>

                        @foreach ($navBusinessGroups as $group)
                            @php($firstLink = $group['links'][0])
                            @php($groupIsActive = collect($group['links'])->contains(fn (array $link): bool => request()->routeIs($link['active'])))
                            <x-nav-link :href="route($firstLink['route'])" :active="$businessActive && $groupIsActive">
                                {{ $group['label'] }}
                            </x-nav-link>
                        @endforeach

                        @if ($navProviderStatusItems !== [])
                            <x-nav-link :href="route('rider.provider-applications.index')" :active="request()->routeIs('rider.provider-applications.*')">
                                {{ __('rider.nav_provider_status') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('rider.provider-applications.index')" :active="request()->routeIs('rider.provider-applications.*')">
                                {{ __('rider.nav_apply_provider') }}
                            </x-nav-link>
                        @endif
                    @endif
                </div>
            </div>

            <div class="hidden gap-3 sm:ms-6 sm:flex sm:items-center">
                <x-language-switcher variant="dark" />

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-bold leading-4 text-slate-600 transition hover:bg-slate-50 hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            <span class="max-w-36 truncate">{{ $user->name }}</span>

                            <span class="ms-1">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route($navIsAdmin ? 'profile.edit' : 'rider.profile.edit')">
                            {{ __('rider.nav_profile') }}
                        </x-dropdown-link>

                        @unless ($navIsAdmin)
                            <x-dropdown-link :href="route('rider.provider-applications.index')">
                                {{ __('rider.nav_apply_provider') }}
                            </x-dropdown-link>

                            @foreach ($navProviderStatusItems as $item)
                                <x-dropdown-link :href="route('rider.provider-applications.index')">
                                    {{ $item['label'] }}: {{ $item['badge'] }}
                                </x-dropdown-link>
                            @endforeach
                        @endunless

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('rider.nav_logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-950 focus:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            @if ($navIsAdmin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('rider.nav_admin_dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">{{ __('rider.nav_users') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.provider-applications.index')" :active="request()->routeIs('admin.provider-applications.*')">{{ __('rider.nav_provider_applications') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rider.marketplace')" :active="request()->routeIs('rider.marketplace', 'rider.products.*')">{{ __('rider.nav_marketplace') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.sellers.index')" :active="request()->routeIs('admin.sellers.*')">{{ __('rider.nav_sellers') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.service-centers.index')" :active="request()->routeIs('admin.service-centers.*')">{{ __('rider.nav_service_centers') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.roadside-providers.index')" :active="request()->routeIs('admin.roadside-providers.*')">{{ __('rider.nav_roadside_providers') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.delivery-partners.index')" :active="request()->routeIs('admin.delivery-partners.*')">{{ __('rider.nav_delivery_partners') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.dealerships.index')" :active="request()->routeIs('admin.dealerships.*')">{{ __('rider.nav_dealerships') }}</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route($navDashboardRoute)" :active="request()->routeIs($navDashboardRoute)">{{ __('rider.nav_dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rider.marketplace')" :active="request()->routeIs('rider.marketplace', 'rider.products.*')">{{ __('rider.nav_marketplace') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rider.cart.index')" :active="request()->routeIs('rider.cart.*')">{{ __('rider.nav_cart') }} @if ($navCartCount > 0)({{ $navCartCount }})@endif</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rider.orders.index')" :active="request()->routeIs('rider.orders.*')">{{ __('rider.nav_orders') }}</x-responsive-nav-link>
                @if ($navShowsRiderGarage)
                    <x-responsive-nav-link :href="route('rider.garage')" :active="request()->routeIs('rider.garage', 'rider.motorcycles.*')">{{ __('rider.nav_garage') }}</x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('rider.services.index')" :active="request()->routeIs('rider.services.*', 'rider.bookings.*')">{{ __('rider.nav_services') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rider.roadside.create')" :active="request()->routeIs('rider.roadside.*')">{{ __('rider.nav_roadside_assistance') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rider.batteries.index')" :active="request()->routeIs('rider.batteries.*')">{{ __('rider.nav_battery_requests') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rider.dealers.index')" :active="request()->routeIs('rider.dealers.*', 'rider.dealer-motorcycles.*')">{{ __('rider.nav_dealerships') }}</x-responsive-nav-link>

                @foreach ($navBusinessGroups as $group)
                    @php($firstLink = $group['links'][0])
                    @php($groupIsActive = collect($group['links'])->contains(fn (array $link): bool => request()->routeIs($link['active'])))
                    <x-responsive-nav-link :href="route($firstLink['route'])" :active="$groupIsActive">{{ $group['label'] }}</x-responsive-nav-link>
                @endforeach

                <x-responsive-nav-link :href="route('rider.provider-applications.index')" :active="request()->routeIs('rider.provider-applications.*')">
                    {{ $navProviderStatusItems !== [] ? __('rider.nav_provider_status') : __('rider.nav_apply_provider') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-slate-200 pb-1 pt-4">
            <div class="px-4">
                <div class="text-base font-bold text-slate-900">{{ $user->name }}</div>
                <div class="text-sm font-medium text-slate-500">{{ $user->email }}</div>
                <div class="mt-3">
                    <x-language-switcher variant="dark" />
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route($navIsAdmin ? 'profile.edit' : 'rider.profile.edit')">
                    {{ __('rider.nav_profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('rider.nav_logout') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
