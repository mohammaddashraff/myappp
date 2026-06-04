@props([
    'title',
    'eyebrow',
    'stats' => [],
])

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">{{ $eyebrow }}</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $title }}</h1>
        <p class="mt-3 text-sm text-slate-600">Manage only your own profile and assigned platform work.</p>
    </div>

    <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($stats as $label => $value)
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-3xl font-black text-slate-950">{{ number_format($value) }}</p>
            </div>
        @endforeach
    </div>

    @if ($slot->isNotEmpty())
        <div class="mt-5">
            {{ $slot }}
        </div>
    @endif
</div>
