@extends('riders.marketplace.layout')

@section('title', 'Book '.$service->name)
@section('active', 'services')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">Book service</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $service->name }}</h1>
        <p class="mt-3 text-sm text-slate-600">{{ $service->service_center_name }} · {{ $service->location }} · EGP {{ number_format((float) $service->estimated_price) }}</p>
    </section>

    <form method="POST" action="{{ route('rider.bookings.store', $service) }}" class="mt-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Motorcycle from My Garage
                <select name="motorcycle_id" class="rounded-md border-slate-300 text-sm">
                    <option value="">No motorcycle selected</option>
                    @foreach ($motorcycles as $motorcycle)
                        <option value="{{ $motorcycle->id }}" @selected((string) old('motorcycle_id') === (string) $motorcycle->id)>{{ $motorcycle->displayBrand() }} {{ $motorcycle->displayModel() }} · {{ $motorcycle->plate_number }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Service date
                <input type="date" name="booking_date" value="{{ old('booking_date', now()->addDay()->toDateString()) }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Preferred time
                <input type="time" name="preferred_time" value="{{ old('preferred_time', '10:00') }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Location option
                <select name="location_option" class="rounded-md border-slate-300 text-sm">
                    <option value="visit_workshop" @selected(old('location_option') === 'visit_workshop')>Visit workshop</option>
                    <option value="pickup_service" @selected(old('location_option') === 'pickup_service') @disabled(! $service->pickup_available)>Pickup service{{ $service->pickup_available ? '' : ' · unavailable' }}</option>
                </select>
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Contact phone number
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $rider->phone_number) }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700 md:col-span-2">
                Rider notes
                <textarea name="notes" rows="4" class="rounded-md border-slate-300 text-sm" placeholder="Any issue details, sound, mileage, or timing notes">{{ old('notes') }}</textarea>
            </label>
        </div>
        <button type="submit" class="mt-6 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Submit Booking</button>
    </form>
@endsection
