@extends('layouts.app')

@section('content')
    <x-role-dashboard-shell title="Seller dashboard" eyebrow="Seller" :stats="[
        'My products' => $productsCount,
        'Active products' => $activeProductsCount,
        'Low stock products' => $lowStockProductsCount,
        'My product orders' => $ordersCount,
        'Pending orders' => $pendingOrdersCount,
        'Completed orders' => $completedOrdersCount,
    ]">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('seller.products.create') }}" class="inline-flex rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Add product</a>
            <a href="{{ route('seller.products.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">My products</a>
            <a href="{{ route('seller.orders.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">My orders</a>
            <a href="{{ route('seller.profile.edit') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Seller profile</a>
        </div>
    </x-role-dashboard-shell>
@endsection
