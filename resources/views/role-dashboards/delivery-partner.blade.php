@extends('layouts.app')

@section('content')
    <x-role-dashboard-shell title="Delivery partner dashboard" eyebrow="Delivery partner" :stats="[
        'Available delivery tasks' => $availableDeliveriesCount,
        'Assigned tasks' => $pendingDeliveriesCount,
        'Active deliveries' => $activeDeliveriesCount,
        'Completed deliveries' => $completedDeliveriesCount,
        'Delivery history' => $deliveryHistoryCount,
    ]">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('delivery-partner.tasks.index', ['filter' => 'available']) }}" class="inline-flex rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Available tasks</a>
            <a href="{{ route('delivery-partner.tasks.index', ['filter' => 'active']) }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Active deliveries</a>
            <a href="{{ route('delivery-partner.tasks.index', ['filter' => 'history']) }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">History</a>
            <a href="{{ route('delivery-partner.profile.edit') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Delivery profile</a>
        </div>
    </x-role-dashboard-shell>
@endsection
