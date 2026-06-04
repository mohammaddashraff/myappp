@extends('riders.marketplace.layout')

@section('title', $motorcycle->fullName())
@section('active', 'dealers')

@section('content')
    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        @if ($motorcycle->image)
            <img src="{{ $motorcycle->image }}" alt="{{ $motorcycle->fullName() }}" class="h-72 w-full object-cover">
        @else
            <div class="flex h-72 w-full items-center justify-center bg-slate-100 text-sm font-black text-slate-400">Motorcycle image</div>
        @endif
        <div class="p-6 sm:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase text-teal-700">{{ ucfirst($motorcycle->condition) }}</p>
                    <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $motorcycle->brand }} {{ $motorcycle->model }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $motorcycle->year }} · {{ $motorcycle->engine_cc }} CC</p>
                </div>
                @if ($canUseRiderActions)
                    <a href="{{ route('rider.dealer-motorcycles.inquiries.create', [$motorcycle->dealer, $motorcycle]) }}" class="inline-flex w-fit justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Send Inquiry</a>
                @else
                    <span class="inline-flex w-fit rounded-md border border-teal-200 bg-teal-50 px-5 py-3 text-sm font-black text-teal-800">Admin monitoring</span>
                @endif
            </div>
            <p class="mt-4 text-3xl font-black">EGP {{ number_format((float) $motorcycle->price) }}</p>
            <p class="mt-4 max-w-3xl text-sm leading-6 text-slate-600">{{ $motorcycle->description }}</p>

            <dl class="mt-6 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    'Dealer name' => $motorcycle->dealer->name,
                    'Dealer location' => $motorcycle->dealer->location,
                    'Installment options' => $motorcycle->installment_available ? ($motorcycle->installment_options ?? 'Available') : 'Not available',
                    'Condition' => ucfirst($motorcycle->condition),
                    'Brand' => $motorcycle->brand,
                    'Model' => $motorcycle->model,
                ] as $label => $value)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                        <dt class="text-xs font-black uppercase text-slate-500">{{ $label }}</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-950">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
@endsection
