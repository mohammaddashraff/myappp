@extends('riders.marketplace.layout')

@section('title', 'Battery Installation')
@section('active', 'batteries')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">Battery replacement request</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $battery->name }}</h1>
        <p class="mt-3 text-sm text-slate-600">{{ $battery->brand }} · {{ $battery->voltage }} · {{ $battery->capacity }} · EGP {{ number_format((float) $battery->price) }}</p>
    </section>

    <form method="POST" action="{{ route('rider.batteries.installation.store', $battery) }}" class="mt-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Motorcycle
                <select name="motorcycle_id" class="rounded-md border-slate-300 text-sm">
                    <option value="">No motorcycle selected</option>
                    @foreach ($motorcycles as $motorcycle)
                        <option value="{{ $motorcycle->id }}" @selected((string) old('motorcycle_id') === (string) $motorcycle->id)>{{ $motorcycle->displayBrand() }} {{ $motorcycle->displayModel() }} · {{ $motorcycle->plate_number }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Contact phone
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $rider->phone_number) }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Preferred date
                <input type="date" name="preferred_date" value="{{ old('preferred_date', now()->addDay()->toDateString()) }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Preferred time
                <input type="time" name="preferred_time" value="{{ old('preferred_time', '12:00') }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700 md:col-span-2">
                Rider location
                <textarea name="location" rows="3" class="rounded-md border-slate-300 text-sm">{{ old('location', $rider->current_address) }}</textarea>
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700 md:col-span-2">
                Notes
                <textarea name="notes" rows="4" class="rounded-md border-slate-300 text-sm">{{ old('notes') }}</textarea>
            </label>
        </div>
        <button type="submit" class="mt-6 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Submit Battery Request</button>
    </form>
@endsection
