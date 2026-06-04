@extends('layouts.app')

@section('content')
    <x-role-dashboard-shell title="Roadside provider dashboard" eyebrow="Roadside provider" :stats="[
        'Available requests' => $availableRequestsCount,
        'Active requests' => $activeRequestsCount,
        'On the way' => $onTheWayRequestsCount,
        'Completed requests' => $completedRequestsCount,
    ]">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('roadside-provider.requests.index', ['filter' => 'available']) }}" class="inline-flex rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Available requests</a>
            <a href="{{ route('roadside-provider.requests.index', ['filter' => 'active']) }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Active requests</a>
            <a href="{{ route('roadside-provider.requests.index', ['filter' => 'completed']) }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Completed</a>
            <a href="{{ route('roadside-provider.profile.edit') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Provider profile</a>
        </div>
    </x-role-dashboard-shell>
@endsection
