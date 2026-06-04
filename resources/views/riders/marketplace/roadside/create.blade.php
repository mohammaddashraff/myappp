@extends('riders.marketplace.layout')

@section('title', 'Roadside Assistance')
@section('active', 'roadside')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">Request flow</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">Roadside Assistance</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">Request towing, flat tire help, fuel delivery, jumpstart, breakdown support, or accident support.</p>
    </section>

    <form method="POST" action="{{ route('rider.roadside.store') }}" class="mt-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Assistance type
                <select name="assistance_type" class="rounded-md border-slate-300 text-sm">
                    @foreach ($assistanceTypes as $assistanceType)
                        <option value="{{ $assistanceType }}" @selected(old('assistance_type') === $assistanceType)>{{ $assistanceType }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Motorcycle from My Garage
                <select name="motorcycle_id" class="rounded-md border-slate-300 text-sm">
                    <option value="">No motorcycle selected</option>
                    @foreach ($motorcycles as $motorcycle)
                        <option value="{{ $motorcycle->id }}" @selected((string) old('motorcycle_id') === (string) $motorcycle->id)>{{ $motorcycle->displayBrand() }} {{ $motorcycle->displayModel() }} · {{ $motorcycle->plate_number }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700 md:col-span-2">
                Current location manually
                <textarea name="location" rows="3" class="rounded-md border-slate-300 text-sm" placeholder="Street, landmark, area, city">{{ old('location') }}</textarea>
            </label>
            <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-5 text-sm font-bold text-slate-500 md:col-span-2">
                Optional map/location placeholder
            </div>
            <label class="grid gap-2 text-sm font-bold text-slate-700 md:col-span-2">
                Description of problem
                <textarea name="description" rows="4" class="rounded-md border-slate-300 text-sm" placeholder="Tell the provider what happened">{{ old('description') }}</textarea>
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Contact phone number
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $rider->phone_number) }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-5 text-sm font-bold text-slate-500">
                Optional image upload placeholder
            </div>
        </div>
        <button type="submit" class="mt-6 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Submit Request</button>
    </form>
@endsection
