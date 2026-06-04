@extends('riders.marketplace.layout')

@section('title', $service->name)
@section('active', 'services')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">{{ $service->category }}</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $service->name }}</h1>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">{{ $service->description }}</p>
            </div>
            @if ($canUseRiderActions)
                <a href="{{ route('rider.bookings.create', $service) }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Book Service</a>
            @else
                <span class="inline-flex w-fit rounded-md border border-teal-200 bg-teal-50 px-5 py-3 text-sm font-black text-teal-800">Admin monitoring</span>
            @endif
        </div>

        <dl class="mt-6 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                'Workshop/service center' => $service->service_center_name,
                'Location' => $service->location,
                'Estimated price' => 'EGP '.number_format((float) $service->estimated_price),
                'Estimated duration' => $service->estimated_duration,
                'Working hours' => $service->working_hours ?? 'Not specified',
                'Pickup service' => $service->pickup_available ? 'Available' : 'Not available',
                'Required motorcycle information' => collect($service->motorcycle_types)->join(', ') ?: 'Motorcycle details from My Garage if available',
                'Notes/instructions' => $service->notes ?? 'No special notes',
            ] as $label => $value)
                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                    <dt class="text-xs font-black uppercase text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>

        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            @if ($canUseRiderActions)
                <a href="{{ route('rider.bookings.create', $service) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-3 text-sm font-black text-white">Book Service</a>
            @endif
            <button type="button" disabled class="inline-flex cursor-not-allowed justify-center rounded-md border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-black text-slate-400">Contact Workshop · Coming Soon</button>
        </div>
    </section>
@endsection
