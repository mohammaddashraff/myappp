@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ $service->exists ? route('service-center.services.update', $service) : route('service-center.services.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf @if ($service->exists) @method('PUT') @endif
            <h1 class="text-3xl font-black text-slate-950">{{ $service->exists ? 'Edit service' : 'Add service' }}</h1>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-bold">Name<input name="name" value="{{ old('name', $service->name) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Category<input name="category" value="{{ old('category', $service->category) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Estimated price<input name="estimated_price" type="number" step="0.01" value="{{ old('estimated_price', $service->estimated_price) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">Duration<input name="estimated_duration" value="{{ old('estimated_duration', $service->estimated_duration) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Working hours<input name="working_hours" value="{{ old('working_hours', $service->working_hours) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">Status<select name="status" class="rounded-md border-slate-300"><option value="active" @selected(old('status', $service->status) === 'active')>Active</option><option value="inactive" @selected(old('status', $service->status) === 'inactive')>Inactive</option></select></label>
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="pickup_available" @checked(old('pickup_available', $service->pickup_available))> Pickup available</label>
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="available_today" @checked(old('available_today', $service->available_today))> Available today</label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Description<textarea name="description" rows="4" class="rounded-md border-slate-300" required>{{ old('description', $service->description) }}</textarea></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Notes<textarea name="notes" rows="3" class="rounded-md border-slate-300">{{ old('notes', $service->notes) }}</textarea></label>
            </div>
            <button class="mt-6 rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Save service</button>
        </form>
    </div>
@endsection
