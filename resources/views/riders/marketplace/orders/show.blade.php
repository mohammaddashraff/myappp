@extends('riders.marketplace.layout')

@section('title', $order->order_number)
@section('active', 'orders')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">Order details</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $order->order_number }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            @include('riders.marketplace.partials.status-badge', ['status' => $order->status])
        </div>

        <div class="mt-6">
            @include('riders.marketplace.partials.timeline', ['timeline' => $timeline, 'status' => $order->status])
        </div>
    </section>

    <section class="mt-5 grid gap-5 lg:grid-cols-[minmax(0,1fr)_340px]">
        <div class="grid gap-4">
            @foreach ($order->items as $item)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase text-teal-700">{{ str($item->product_type)->replace('_', ' ')->title() }}</p>
                            <h2 class="mt-1 text-lg font-black text-slate-950">{{ $item->product_name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">EGP {{ number_format((float) $item->product_price) }} × {{ $item->quantity }}</p>
                        </div>
                        <p class="font-black text-slate-950">EGP {{ number_format((float) $item->total_price) }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm lg:self-start">
            <h2 class="text-xl font-black text-slate-950">Summary</h2>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span class="font-black">EGP {{ number_format((float) $order->subtotal) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Delivery fee</span><span class="font-black">EGP {{ number_format((float) $order->delivery_fee) }}</span></div>
                <div class="flex justify-between text-base"><span class="font-black">Total</span><span class="font-black">EGP {{ number_format((float) $order->total) }}</span></div>
                <div class="border-t border-slate-200 pt-3">
                    <p class="text-xs font-black uppercase text-slate-500">Delivery method</p>
                    <p class="font-bold">{{ str($order->delivery_method)->replace('_', ' ')->title() }}</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase text-slate-500">Payment method</p>
                    <p class="font-bold">{{ str($order->payment_method)->replace('_', ' ')->title() }}</p>
                </div>
                @if ($order->address)
                    <div>
                        <p class="text-xs font-black uppercase text-slate-500">Address</p>
                        <p class="font-bold">{{ $order->address }}</p>
                    </div>
                @endif
            </div>
        </aside>
    </section>
@endsection
