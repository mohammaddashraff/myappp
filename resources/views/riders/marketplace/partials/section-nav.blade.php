@props(['active' => 'marketplace'])

@php
    $canUseRiderActions = $canUseRiderActions ?? true;
    $groups = [
        'Browse' => [
            ['key' => 'marketplace', 'label' => 'Home', 'route' => route('rider.marketplace')],
            ['key' => 'accessories', 'label' => 'Accessories', 'route' => route('rider.products.accessories')],
            ['key' => 'spare-parts', 'label' => 'Spare Parts', 'route' => route('rider.products.spare-parts')],
            ['key' => 'batteries', 'label' => 'Batteries', 'route' => route('rider.batteries.index')],
        ],
        'Services' => [
            ['key' => 'services', 'label' => 'Workshops', 'route' => route('rider.services.index')],
            ['key' => 'dealers', 'label' => 'Dealers', 'route' => route('rider.dealers.index')],
        ],
    ];

    if ($canUseRiderActions) {
        $groups['Services'][] = ['key' => 'roadside', 'label' => 'Roadside', 'route' => route('rider.roadside.create')];
        $groups['Tracking'] = [
            ['key' => 'cart', 'label' => 'Cart', 'route' => route('rider.cart.index')],
            ['key' => 'orders', 'label' => 'Orders', 'route' => route('rider.orders.index')],
            ['key' => 'bookings', 'label' => 'Bookings', 'route' => route('rider.bookings.index')],
            ['key' => 'requests', 'label' => 'Requests', 'route' => route('rider.requests.index')],
        ];
    }
@endphp

<nav class="mb-5 rounded-lg border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-nowrap gap-3 overflow-x-auto px-4 py-3 sm:flex-wrap sm:overflow-visible">
        @foreach ($groups as $group => $links)
            <div class="flex shrink-0 items-center gap-2 sm:flex-wrap">
                <span class="me-1 text-[11px] font-black uppercase tracking-wide text-slate-400">{{ $group }}</span>
                @foreach ($links as $link)
                    <a href="{{ $link['route'] }}" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-black transition {{ $active === $link['key'] ? 'bg-slate-950 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</nav>
