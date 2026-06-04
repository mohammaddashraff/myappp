@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-3xl font-black text-slate-950">My services</h1>
            <a href="{{ route('service-center.services.create') }}" class="rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Add service</a>
        </div>
        @include('riders.marketplace.partials.flash')
        <div class="mt-5 grid gap-4">
            @forelse ($services as $service)
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div><p class="font-black">{{ $service->name }}</p><p class="text-sm text-slate-500">{{ $service->category }} · EGP {{ number_format((float) $service->estimated_price) }}</p></div>
                        <div class="flex gap-2">
                            <a href="{{ route('service-center.services.edit', $service) }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-bold">Edit</a>
                            <form method="POST" action="{{ route('service-center.services.destroy', $service) }}">@csrf @method('DELETE')<button class="rounded-md border border-rose-200 px-3 py-2 text-sm font-bold text-rose-700">Delete</button></form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-slate-200 bg-white p-8 text-center font-bold text-slate-500">No services yet.</div>
            @endforelse
        </div>
        <div class="mt-5">{{ $services->links() }}</div>
    </div>
@endsection
