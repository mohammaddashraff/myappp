<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('rider.view_motorcycle') }} | {{ __('rider.brand') }}</title>

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
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-sm font-bold uppercase text-teal-700">
                                    {{ __('rider.motorcycle_details') }}
                                </p>
                                <h1 class="mt-2 text-3xl font-black leading-tight text-slate-950 sm:text-4xl">{{ $motorcycle->displayBrand() }} {{ $motorcycle->displayModel() }}</h1>
                                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                                    {{ config('motorcycles.types.'.$motorcycle->type, $motorcycle->type) }} · {{ $motorcycle->year }} · {{ $motorcycle->plate_number }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('rider.motorcycles.edit', $motorcycle) }}" class="inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white transition hover:bg-slate-800">
                                    {{ __('rider.edit_motorcycle') }}
                                </a>
                                <form method="POST" action="{{ route('rider.motorcycles.destroy', $motorcycle) }}" onsubmit="return confirm('{{ __('rider.confirm_delete_motorcycle') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-rose-200 bg-white px-5 py-3 text-sm font-black text-rose-700 transition hover:bg-rose-50">
                                        {{ __('rider.delete_motorcycle') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                        <section class="rounded-lg border border-slate-200 bg-white p-6 text-slate-950 shadow-sm sm:p-8">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.brand_label') }}</p>
                                    <p class="mt-2 text-base font-bold">{{ $motorcycle->displayBrand() }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.model_label') }}</p>
                                    <p class="mt-2 text-base font-bold">{{ $motorcycle->displayModel() }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.motorcycle_type') }}</p>
                                    <p class="mt-2 text-base font-bold">{{ config('motorcycles.types.'.$motorcycle->type, $motorcycle->type) }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.manufacturing_year') }}</p>
                                    <p class="mt-2 text-base font-bold">{{ $motorcycle->year }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.engine_cc') }}</p>
                                    <p class="mt-2 text-base font-bold">{{ $motorcycle->engine_cc }} CC</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.plate_number') }}</p>
                                    <p class="mt-2 text-base font-bold">{{ $motorcycle->plate_number }}</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 sm:col-span-2">
                                    <p class="text-xs font-black uppercase text-slate-500">{{ __('rider.color') }}</p>
                                    <p class="mt-2 text-base font-bold">{{ $motorcycle->color ?? __('rider.not_recorded') }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="space-y-5">
                            @foreach ([
                                'image' => 'motorcycle_image',
                                'ownership_license_image' => 'ownership_license_image',
                                'motorcycle_registration_image' => 'motorcycle_registration_image',
                            ] as $field => $labelKey)
                                <article class="rounded-lg border border-slate-200 bg-white p-5 text-slate-950 shadow-sm">
                                    <p class="text-sm font-black uppercase text-teal-700">{{ __('rider.'.$labelKey) }}</p>
                                    @if ($motorcycle->{$field})
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($motorcycle->{$field}) }}" alt="{{ __('rider.'.$labelKey) }}" class="mt-4 h-52 w-full rounded-md object-cover">
                                    @else
                                        <p class="mt-4 text-sm text-slate-500">{{ __('rider.not_recorded') }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </section>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
