@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ $listing->exists ? route('dealership.listings.update', $listing) : route('dealership.listings.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf @if ($listing->exists) @method('PUT') @endif
            <h1 class="text-3xl font-black text-slate-950">{{ $listing->exists ? 'Edit listing' : 'Add listing' }}</h1>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-bold">Brand<input name="brand" value="{{ old('brand', $listing->brand) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Model<input name="model" value="{{ old('model', $listing->model) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Year<input name="year" type="number" value="{{ old('year', $listing->year) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">Engine CC<input name="engine_cc" type="number" value="{{ old('engine_cc', $listing->engine_cc) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">Condition<select name="condition" class="rounded-md border-slate-300"><option value="new" @selected(old('condition', $listing->condition) === 'new')>New</option><option value="used" @selected(old('condition', $listing->condition) === 'used')>Used</option></select></label>
                <label class="grid gap-2 text-sm font-bold">Price<input name="price" type="number" step="0.01" value="{{ old('price', $listing->price) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">Status<select name="status" class="rounded-md border-slate-300"><option value="active" @selected(old('status', $listing->status) === 'active')>Active</option><option value="inactive" @selected(old('status', $listing->status) === 'inactive')>Inactive</option><option value="sold" @selected(old('status', $listing->status) === 'sold')>Sold</option></select></label>
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="installment_available" @checked(old('installment_available', $listing->installment_available))> Installment available</label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Image URL<input name="image" value="{{ old('image', $listing->image) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Installment options<textarea name="installment_options" rows="3" class="rounded-md border-slate-300">{{ old('installment_options', $listing->installment_options) }}</textarea></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Description<textarea name="description" rows="4" class="rounded-md border-slate-300">{{ old('description', $listing->description) }}</textarea></label>
            </div>
            <button class="mt-6 rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Save listing</button>
        </form>
    </div>
@endsection
