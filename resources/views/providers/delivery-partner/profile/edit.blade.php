@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('delivery-partner.profile.update') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf @method('PATCH')
            <h1 class="text-3xl font-black text-slate-950">Delivery profile</h1>
            @include('riders.marketplace.partials.flash')
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-bold">Full name<input name="full_name" value="{{ old('full_name', $profile->full_name) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Phone<input name="phone" value="{{ old('phone', $profile->phone) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">National ID<input name="national_id" value="{{ old('national_id', $profile->national_id) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">License number<input name="license_number" value="{{ old('license_number', $profile->license_number) }}" class="rounded-md border-slate-300"></label>
            </div>
            <button class="mt-6 rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Save profile</button>
        </form>
    </div>
@endsection
