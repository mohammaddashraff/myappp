@extends('layouts.app')

@section('content')
    <x-role-dashboard-shell title="Service center dashboard" eyebrow="Service center" :stats="[
        'My services' => $servicesCount,
        'Pending bookings' => $pendingBookingsCount,
        'Accepted bookings' => $acceptedBookingsCount,
        'Completed bookings' => $completedBookingsCount,
    ]">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('service-center.services.create') }}" class="inline-flex rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Add service</a>
            <a href="{{ route('service-center.services.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">My services</a>
            <a href="{{ route('service-center.bookings.index') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Bookings</a>
            <a href="{{ route('service-center.profile.edit') }}" class="inline-flex rounded-md border border-slate-300 bg-white px-5 py-3 text-sm font-black text-slate-800">Profile</a>
        </div>
    </x-role-dashboard-shell>
@endsection
