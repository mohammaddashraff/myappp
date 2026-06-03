<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('rider.garage_title') }} | {{ __('rider.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <section class="mx-auto grid max-w-7xl gap-6 md:grid-cols-[240px_minmax(0,1fr)] xl:grid-cols-[280px_minmax(0,1fr)]">
                @include('riders.partials.sidebar', ['active' => 'garage'])

                <div class="py-2 lg:py-4">
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-sm font-bold uppercase text-teal-700">
                                    {{ __('rider.my_garage') }}
                                </p>
                                <h1 class="mt-2 text-3xl font-black leading-tight text-slate-950 sm:text-4xl">{{ __('rider.garage_heading') }}</h1>
                                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                                    {{ __('rider.garage_ready_intro') }}
                                </p>
                            </div>

                            <div class="flex flex-col items-start gap-3 lg:items-end">
                                <span class="inline-flex w-fit rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-black text-slate-800">
                                    {{ __('rider.motorcycle_count', ['count' => $motorcycles->count()]) }}
                                </span>
                                @if ($rider)
                                    <a href="{{ route('rider.motorcycles.create') }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-slate-800">
                                        {{ __('rider.add_motorcycle') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($status)
                        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                            {{ __('rider.garage_status_'.str_replace('motorcycle-', '', $status)) }}
                        </div>
                    @endif

                    @if (! $rider)
                        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-6 text-slate-950 shadow-sm">
                            <h2 class="text-xl font-black">{{ __('rider.profile_missing') }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                {{ __('rider.profile_missing_help') }}
                            </p>
                        </section>
                    @elseif ($motorcycles->isEmpty())
                        <section class="mt-5 rounded-lg border border-slate-200 bg-white p-6 text-slate-950 shadow-sm">
                            <h2 class="text-xl font-black">{{ __('rider.no_motorcycles') }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                {{ __('rider.no_motorcycles_cta') }}
                            </p>
                            <a href="{{ route('rider.motorcycles.create') }}" class="mt-5 inline-flex justify-center rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white transition hover:bg-slate-800">
                                {{ __('rider.add_motorcycle') }}
                            </a>
                        </section>
                    @else
                        <div class="mt-5 grid gap-4">
                            @foreach ($motorcycles as $motorcycle)
                                <article class="overflow-hidden rounded-lg border border-slate-200 bg-white text-slate-950 shadow-sm">
                                    <div class="grid lg:grid-cols-[180px_minmax(0,1fr)]">
                                        <div class="border-b border-slate-200 bg-slate-100 p-4 lg:border-b-0 lg:border-e">
                                            @if ($motorcycle->image)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($motorcycle->image) }}" alt="{{ $motorcycle->displayBrand() }}" class="aspect-[4/3] w-full rounded-md object-cover">
                                            @else
                                                <div class="flex aspect-[4/3] w-full items-center justify-center rounded-md border border-dashed border-slate-300 bg-white text-sm font-bold text-slate-400">
                                                    {{ __('rider.image') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-5">
                                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                                <div>
                                                    <p class="text-xs font-black uppercase text-teal-700">
                                                        {{ $motorcycle->nickname ?? $motorcycle->displayBrand() }}
                                                    </p>
                                                    <h2 class="mt-1 text-2xl font-black leading-tight text-slate-950">
                                                        {{ $motorcycle->displayBrand() }} {{ $motorcycle->displayModel() }}
                                                    </h2>
                                                    <p class="mt-2 text-sm font-semibold text-slate-600">
                                                        {{ config('motorcycles.types.'.$motorcycle->type, $motorcycle->type) }} · {{ $motorcycle->year ?? __('rider.not_recorded') }}
                                                    </p>
                                                </div>

                                                @if ($motorcycle->is_primary)
                                                    <span class="inline-flex w-fit rounded-full bg-teal-100 px-3 py-1 text-xs font-black text-teal-800">
                                                        {{ __('rider.primary') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <dl class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                                                    <dt class="text-xs font-black uppercase text-slate-500">{{ __('rider.plate_number') }}</dt>
                                                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ $motorcycle->plate_number ?? __('rider.not_recorded') }}</dd>
                                                </div>

                                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                                                    <dt class="text-xs font-black uppercase text-slate-500">{{ __('rider.engine_cc') }}</dt>
                                                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ $motorcycle->engine_cc ? $motorcycle->engine_cc.' CC' : __('rider.not_recorded') }}</dd>
                                                </div>

                                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                                                    <dt class="text-xs font-black uppercase text-slate-500">{{ __('rider.color') }}</dt>
                                                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ $motorcycle->color ?? __('rider.not_recorded') }}</dd>
                                                </div>

                                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                                                    <dt class="text-xs font-black uppercase text-slate-500">{{ __('rider.owner') }}</dt>
                                                    <dd class="mt-1 text-sm font-bold text-slate-950">{{ $motorcycle->owner_name ?? __('rider.not_recorded') }}</dd>
                                                </div>
                                            </dl>

                                            <div class="mt-5 flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
                                                <p class="text-sm text-slate-500">
                                                    {{ __('rider.garage_ready_intro') }}
                                                </p>

                                                <div class="flex flex-col gap-2 sm:flex-row">
                                                    <a href="{{ route('rider.motorcycles.show', $motorcycle) }}" class="inline-flex justify-center rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                                                        {{ __('rider.view_motorcycle') }}
                                                    </a>
                                                    <a href="{{ route('rider.motorcycles.edit', $motorcycle) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-4 py-2 text-sm font-black text-white transition hover:bg-slate-800">
                                                        {{ __('rider.edit_motorcycle') }}
                                                    </a>
                                                    <form method="POST" action="{{ route('rider.motorcycles.destroy', $motorcycle) }}" onsubmit="return confirm('{{ __('rider.confirm_delete_motorcycle') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex w-full justify-center rounded-md border border-rose-200 bg-white px-4 py-2 text-sm font-black text-rose-700 transition hover:bg-rose-50">
                                                            {{ __('rider.delete_motorcycle') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </main>
    </body>
</html>
