@extends('riders.marketplace.layout')

@section('title', $dealer->name)
@section('active', 'dealers')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">Showroom</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $dealer->name }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ $dealer->location }} · {{ collect($dealer->brands_available)->join(', ') }}</p>
            </div>
            @if ($canUseRiderActions)
                <a href="{{ route('rider.dealers.inquiries.create', $dealer) }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Send Inquiry</a>
            @else
                <span class="inline-flex w-fit rounded-md border border-teal-200 bg-teal-50 px-5 py-3 text-sm font-black text-teal-800">Admin monitoring</span>
            @endif
        </div>
    </section>

    @if ($dealer->motorcycles->isEmpty())
        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-xl font-black text-slate-950">No motorcycles found</h2>
        </section>
    @else
        <section class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($dealer->motorcycles as $motorcycle)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-xl font-black">{{ $motorcycle->fullName() }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $motorcycle->engine_cc }} CC · {{ ucfirst($motorcycle->condition) }}</p>
                    <p class="mt-3 text-2xl font-black">EGP {{ number_format((float) $motorcycle->price) }}</p>
                    <div class="mt-5 grid gap-2 sm:grid-cols-2">
                        <a href="{{ route('rider.dealer-motorcycles.show', $motorcycle) }}" class="inline-flex justify-center rounded-md border border-slate-200 px-4 py-2.5 text-sm font-black text-slate-700">View Details</a>
                        @if ($canUseRiderActions)
                            <a href="{{ route('rider.dealer-motorcycles.inquiries.create', [$dealer, $motorcycle]) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white">Send Inquiry</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>
    @endif
@endsection
