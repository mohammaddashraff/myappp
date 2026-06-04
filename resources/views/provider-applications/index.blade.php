@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-bold uppercase text-teal-700">Provider applications</p>
            <h1 class="mt-2 text-3xl font-black text-slate-950">My applications</h1>
            <a href="{{ route('rider.provider-applications.create') }}" class="mt-4 inline-flex rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Apply as provider</a>
        </div>

        <div class="mt-5 grid gap-3">
            @forelse ($applications as $application)
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase text-teal-700">{{ str($application->requested_role)->headline() }}</p>
                            <h2 class="mt-1 text-xl font-black text-slate-950">{{ $application->business_name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $application->city }} · {{ $application->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-700">{{ str($application->status)->headline() }}</span>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center">
                    <h2 class="text-xl font-black text-slate-950">No applications yet</h2>
                    <p class="mt-2 text-sm text-slate-500">Apply to become a seller, service center, provider, delivery partner, or dealership.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
