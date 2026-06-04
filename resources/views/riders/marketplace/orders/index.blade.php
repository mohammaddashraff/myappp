@extends('riders.marketplace.layout')

@section('title', 'My Orders')
@section('active', 'orders')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">Tracking flow</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">My Orders</h1>
        <p class="mt-3 text-sm leading-6 text-slate-600">Track product orders created from marketplace checkout.</p>
    </section>

    @if ($orders->isEmpty())
        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-xl font-black text-slate-950">No orders yet</h2>
            <p class="mt-2 text-sm text-slate-500">Your confirmed marketplace orders will appear here.</p>
        </section>
    @else
        <section class="mt-5 grid gap-4">
            @foreach ($orders as $order)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase text-teal-700">{{ $order->order_number }}</p>
                            <h2 class="mt-1 text-xl font-black text-slate-950">EGP {{ number_format((float) $order->total) }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $order->items->count() }} items · {{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex flex-col gap-3 sm:items-end">
                            @include('riders.marketplace.partials.status-badge', ['status' => $order->status])
                            <a href="{{ route('rider.orders.show', $order) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">View Details</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
@endsection
