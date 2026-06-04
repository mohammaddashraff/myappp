@extends('layouts.app')

@section('content')
    <x-role-dashboard-shell title="Dealership dashboard" eyebrow="Dealership" :stats="[
        'My motorcycle listings' => $listingsCount,
        'Active listings' => $activeListingsCount,
        'Sold listings' => $soldListingsCount,
        'New inquiries' => $newInquiriesCount,
        'Inquiries' => $inquiriesCount,
    ]">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('dealership.listings.create') }}" class="inline-flex rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Add motorcycle listing</a>
            <a href="{{ route('dealership.listings.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">My listings</a>
            <a href="{{ route('dealership.inquiries.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Inquiries</a>
            <a href="{{ route('dealership.profile.edit') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Dealership profile</a>
        </div>
    </x-role-dashboard-shell>
@endsection
