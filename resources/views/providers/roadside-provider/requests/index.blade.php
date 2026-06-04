@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-black text-slate-950">Roadside requests</h1>
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach (['available' => 'Available', 'active' => 'Active', 'completed' => 'Completed'] as $key => $label)
                <a href="{{ route('roadside-provider.requests.index', ['filter' => $key]) }}" class="rounded-md border px-4 py-2 text-sm font-black {{ $filter === $key ? 'border-slate-950 bg-slate-950 text-white' : 'border-slate-300 bg-white text-slate-700' }}">{{ $label }}</a>
            @endforeach
        </div>
        @include('riders.marketplace.partials.flash')
        <div class="mt-5 grid gap-4">
            @forelse ($requests as $roadsideRequest)
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div><p class="font-black">{{ $roadsideRequest->request_number }} · {{ $roadsideRequest->assistance_type }}</p><p class="text-sm text-slate-500">{{ $roadsideRequest->location }}</p></div>
                        @if ($roadsideRequest->roadside_provider_profile_id === null)
                            <form method="POST" action="{{ route('roadside-provider.requests.accept', $roadsideRequest) }}">@csrf @method('PATCH')<button class="rounded-md bg-slate-950 px-4 py-2 text-sm font-black text-white">Accept</button></form>
                        @else
                            <form method="POST" action="{{ route('roadside-provider.requests.update', $roadsideRequest) }}" class="flex gap-2">@csrf @method('PATCH')<select name="status" class="rounded-md border-slate-300 text-sm">@foreach (\App\Models\RoadsideRequest::statuses() as $status)<option value="{{ $status }}" @selected($roadsideRequest->status === $status)>{{ str($status)->headline() }}</option>@endforeach</select><button class="rounded-md bg-slate-950 px-4 py-2 text-sm font-black text-white">Update</button></form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-slate-200 bg-white p-8 text-center font-bold text-slate-500">No requests found.</div>
            @endforelse
        </div>
        <div class="mt-5">{{ $requests->links() }}</div>
    </div>
@endsection
