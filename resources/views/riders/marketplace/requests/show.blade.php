@extends('riders.marketplace.layout')

@section('title', 'Request Details')
@section('active', 'requests')

@section('content')
    @php
        $number = match ($type) {
            'roadside', 'battery' => $record->request_number,
            'dealer' => $record->inquiry_number,
        };
        $label = match ($type) {
            'roadside' => 'Roadside Assistance',
            'battery' => 'Battery Replacement',
            'dealer' => 'Dealer Inquiry',
        };
    @endphp

    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase text-teal-700">{{ $label }}</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $number }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ $record->created_at->format('M d, Y h:i A') }}</p>
            </div>
            @include('riders.marketplace.partials.status-badge', ['status' => $record->status])
        </div>
        <div class="mt-6">
            @if (in_array($record->status, ['cancelled', 'rejected'], true))
                <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-800">{{ str($record->status)->replace('_', ' ')->title() }}</div>
            @else
                @include('riders.marketplace.partials.timeline', ['timeline' => $timeline, 'status' => $record->status])
            @endif
        </div>
    </section>

    <section class="mt-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @if ($type === 'roadside')
            @php
                $details = [
                    'Assistance type' => $record->assistance_type,
                    'Motorcycle' => $record->motorcycle ? $record->motorcycle->displayBrand().' '.$record->motorcycle->displayModel() : 'Not selected',
                    'Location' => $record->location,
                    'Description' => $record->description ?? 'No description',
                    'Contact phone' => $record->contact_phone,
                    'Current status' => $record->statusLabel(),
                ];
            @endphp
        @elseif ($type === 'battery')
            @php
                $details = [
                    'Selected battery' => $record->battery->name,
                    'Motorcycle' => $record->motorcycle ? $record->motorcycle->displayBrand().' '.$record->motorcycle->displayModel() : 'Not selected',
                    'Rider location' => $record->location,
                    'Preferred date/time' => $record->preferred_date->format('M d, Y').' · '.substr($record->preferred_time, 0, 5),
                    'Contact phone' => $record->contact_phone,
                    'Notes' => $record->notes ?? 'No notes',
                    'Current status' => $record->statusLabel(),
                ];
            @endphp
        @else
            @php
                $details = [
                    'Dealer' => $record->dealer->name,
                    'Interested motorcycle' => $record->motorcycle?->fullName() ?? 'General inquiry',
                    'Rider name' => $record->rider_name,
                    'Phone' => $record->phone,
                    'Preferred contact method' => ucfirst($record->preferred_contact_method),
                    'Message' => $record->message,
                    'Current status' => $record->statusLabel(),
                ];
            @endphp
        @endif

        <dl class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($details as $label => $value)
                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                    <dt class="text-xs font-black uppercase text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
@endsection
