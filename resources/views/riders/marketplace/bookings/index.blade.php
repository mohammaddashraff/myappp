@extends('riders.marketplace.layout')

@section('title', 'My Bookings')
@section('active', 'bookings')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">Tracking flow</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">My Bookings</h1>
    </section>

    @if ($bookings->isEmpty())
        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-xl font-black text-slate-950">No bookings yet</h2>
            <p class="mt-2 text-sm text-slate-500">Service bookings will appear here after submission.</p>
        </section>
    @else
        <section class="mt-5 grid gap-4">
            @foreach ($bookings as $booking)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase text-teal-700">{{ $booking->booking_number }}</p>
                            <h2 class="mt-1 text-xl font-black text-slate-950">{{ $booking->service->name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $booking->service->service_center_name }} · {{ $booking->booking_date->format('M d, Y') }} · {{ substr($booking->preferred_time, 0, 5) }}</p>
                            <p class="mt-1 text-sm font-bold text-slate-700">EGP {{ number_format((float) $booking->estimated_price) }}</p>
                        </div>
                        <div class="flex flex-col gap-3 sm:items-end">
                            @include('riders.marketplace.partials.status-badge', ['status' => $booking->status])
                            <a href="{{ route('rider.bookings.show', $booking) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">View Details</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="mt-6">{{ $bookings->links() }}</div>
    @endif
@endsection
