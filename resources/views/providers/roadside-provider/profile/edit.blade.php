@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('roadside-provider.profile.update') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf @method('PATCH')
            <h1 class="text-3xl font-black text-slate-950">Roadside provider profile</h1>
            @include('riders.marketplace.partials.flash')
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-bold">Provider name<input name="provider_name" value="{{ old('provider_name', $profile->provider_name) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">Phone<input name="phone" value="{{ old('phone', $profile->phone) }}" class="rounded-md border-slate-300" required></label>
                <label class="grid gap-2 text-sm font-bold">City<input name="city" value="{{ old('city', $profile->city) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold">Coverage area<input name="coverage_area" value="{{ old('coverage_area', $profile->coverage_area) }}" class="rounded-md border-slate-300"></label>
                <label class="grid gap-2 text-sm font-bold sm:col-span-2">Address<input name="address" value="{{ old('address', $profile->address) }}" class="rounded-md border-slate-300"></label>
            </div>
            <button class="mt-6 rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Save profile</button>
        </form>
    </div>
@endsection
