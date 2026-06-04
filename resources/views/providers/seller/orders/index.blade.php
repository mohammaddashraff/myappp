@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-black text-slate-950">My product orders</h1>
        @include('riders.marketplace.partials.flash')
        <div class="mt-5 grid gap-4">
            @forelse ($orders as $order)
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div><p class="font-black">{{ $order->order_number }}</p><p class="text-sm text-slate-500">{{ $order->created_at->format('M d, Y') }}</p></div>
                        <form method="POST" action="{{ route('seller.orders.update', $order) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="status" class="rounded-md border-slate-300 text-sm">@foreach (\App\Models\Order::statuses() as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ str($status)->headline() }}</option>@endforeach</select>
                            <button class="rounded-md bg-slate-950 px-4 py-2 text-sm font-black text-white">Update</button>
                        </form>
                    </div>
                    <div class="mt-3 text-sm text-slate-600">{{ $order->items->pluck('product_name')->join(', ') }}</div>
                </div>
            @empty
                <div class="rounded-lg border border-slate-200 bg-white p-8 text-center font-bold text-slate-500">No orders yet.</div>
            @endforelse
        </div>
        <div class="mt-5">{{ $orders->links() }}</div>
    </div>
@endsection
