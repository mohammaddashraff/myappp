@extends('riders.marketplace.layout')

@section('title', $booking->booking_number)
@section('active', 'bookings')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">Booking details</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $booking->booking_number }}</h1>
            </div>
            @include('riders.marketplace.partials.status-badge', ['status' => $booking->status])
        </div>
        <div class="mt-6">
            @if (in_array($booking->status, ['cancelled', 'rejected'], true))
                <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-800">{{ $booking->statusLabel() }}</div>
            @else
                @include('riders.marketplace.partials.timeline', ['timeline' => $timeline, 'status' => $booking->status])
            @endif
        </div>
    </section>

    <section class="mt-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <dl class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                'Service name' => $booking->service->name,
                'Workshop/service center' => $booking->service->service_center_name,
                'Location' => $booking->service->location,
                'Motorcycle details' => $booking->motorcycle ? $booking->motorcycle->displayBrand().' '.$booking->motorcycle->displayModel().' · '.$booking->motorcycle->plate_number : 'Not selected',
                'Booking date' => $booking->booking_date->format('M d, Y'),
                'Preferred time' => substr($booking->preferred_time, 0, 5),
                'Estimated price' => 'EGP '.number_format((float) $booking->estimated_price),
                'Current status' => $booking->statusLabel(),
                'Rider notes' => $booking->notes ?? 'No notes',
            ] as $label => $value)
                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                    <dt class="text-xs font-black uppercase text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
@endsection
