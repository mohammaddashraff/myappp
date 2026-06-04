@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('rider.provider-applications.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <p class="text-sm font-bold uppercase text-teal-700">Apply as provider</p>
            <h1 class="mt-2 text-3xl font-black text-slate-950">Business application</h1>

            @if ($errors->any())
                <div class="mt-5 rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-800">Please review the highlighted fields.</div>
            @endif

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Requested role
                    <select name="requested_role" class="rounded-md border-slate-300 text-sm" required>
                        @foreach ($providerRoles as $role)
                            <option value="{{ $role }}" @selected(old('requested_role') === $role)>{{ str($role)->headline() }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('requested_role')" />
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Business/display name
                    <input name="business_name" value="{{ old('business_name') }}" class="rounded-md border-slate-300 text-sm" required>
                    <x-input-error :messages="$errors->get('business_name')" />
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Public display name
                    <input name="display_name" value="{{ old('display_name') }}" class="rounded-md border-slate-300 text-sm">
                    <x-input-error :messages="$errors->get('display_name')" />
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Phone
                    <input name="phone" value="{{ old('phone') }}" class="rounded-md border-slate-300 text-sm" required dir="ltr">
                    <x-input-error :messages="$errors->get('phone')" />
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    City
                    <input name="city" value="{{ old('city') }}" class="rounded-md border-slate-300 text-sm" required>
                    <x-input-error :messages="$errors->get('city')" />
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700 sm:col-span-2">
                    Address
                    <input name="address" value="{{ old('address') }}" class="rounded-md border-slate-300 text-sm" required>
                    <x-input-error :messages="$errors->get('address')" />
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700 sm:col-span-2">
                    Description
                    <textarea name="description" rows="4" class="rounded-md border-slate-300 text-sm">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </label>
            </div>

            <button type="submit" class="mt-6 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Submit application</button>
        </form>
    </div>
@endsection
