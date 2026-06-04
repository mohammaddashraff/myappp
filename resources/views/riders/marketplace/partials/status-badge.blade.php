@props(['status'])

@php
    $statusText = str($status)->replace('_', ' ')->title();
    $classes = match ($status) {
        'pending' => 'bg-amber-100 text-amber-800',
        'accepted', 'assigned', 'confirmed', 'contacted', 'preparing', 'picked_up', 'scheduled', 'on_the_way', 'out_for_delivery' => 'bg-sky-100 text-sky-800',
        'completed', 'delivered', 'closed' => 'bg-emerald-100 text-emerald-800',
        'cancelled', 'failed', 'rejected' => 'bg-rose-100 text-rose-800',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-black {{ $classes }}">
    {{ $statusText }}
</span>
