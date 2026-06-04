@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-3xl font-black text-slate-950">Motorcycle listings</h1>
            <a href="{{ route('dealership.listings.create') }}" class="rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Add listing</a>
        </div>
        @include('riders.marketplace.partials.flash')
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            @forelse ($listings as $listing)
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="font-black">{{ $listing->fullName() }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ str($listing->condition)->headline() }} · EGP {{ number_format((float) $listing->price) }} · {{ str($listing->status)->headline() }}</p>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('dealership.listings.edit', $listing) }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-bold">Edit</a>
                        <form method="POST" action="{{ route('dealership.listings.destroy', $listing) }}">@csrf @method('DELETE')<button class="rounded-md border border-rose-200 px-3 py-2 text-sm font-bold text-rose-700">Delete</button></form>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-slate-200 bg-white p-8 text-center font-bold text-slate-500 md:col-span-2">No listings yet.</div>
            @endforelse
        </div>
        <div class="mt-5">{{ $listings->links() }}</div>
    </div>
@endsection
