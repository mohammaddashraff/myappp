@props(['timeline', 'status'])

@php
    $currentIndex = collect($timeline)->search($status);
@endphp

<div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
    @foreach ($timeline as $index => $step)
        @php
            $isReached = $currentIndex !== false && $index <= $currentIndex;
        @endphp
        <div class="rounded-lg border {{ $isReached ? 'border-teal-200 bg-teal-50' : 'border-slate-200 bg-slate-50' }} px-4 py-3">
            <p class="text-xs font-black uppercase {{ $isReached ? 'text-teal-700' : 'text-slate-400' }}">
                Step {{ $index + 1 }}
            </p>
            <p class="mt-1 text-sm font-black {{ $isReached ? 'text-teal-950' : 'text-slate-500' }}">
                {{ str($step)->replace('_', ' ')->title() }}
            </p>
        </div>
    @endforeach
</div>
