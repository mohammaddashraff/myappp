@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('seller.profile.update') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf @method('PATCH')
            <h1 class="text-3xl font-black text-slate-950">Seller profile</h1>
            @include('riders.marketplace.partials.flash')
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-bold">Store name<input name="store_name" value="{{ old('store_name', $profile->store_name) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Seller type<input name="seller_type" value="{{ old('seller_type', $profile->seller_type) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">Phone<input name="phone" value="{{ old('phone', $profile->phone) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">City<input name="city" value="{{ old('city', $profile->city) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Address<input name="address" value="{{ old('address', $profile->address) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Logo URL<input name="logo" value="{{ old('logo', $profile->logo) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Description<textarea name="description" rows="4" class="rounded-md border-slate-300">{{ old('description', $profile->description) }}</textarea></label>
            </div>
            <button class="mt-6 rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Save profile</button>
        </form>
    </div>
@endsection
