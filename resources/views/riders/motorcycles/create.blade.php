<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('rider.add_motorcycle') }} | {{ __('rider.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <style>[x-cloak] { display: none !important; }</style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <section class="mx-auto grid max-w-7xl gap-6 md:grid-cols-[240px_minmax(0,1fr)] xl:grid-cols-[280px_minmax(0,1fr)]">
                @include('riders.partials.sidebar', ['active' => 'garage', 'showAddButton' => false])

                <div class="grid gap-6 py-2 lg:grid-cols-[0.72fr_1.28fr] lg:py-4">
                    <div class="flex flex-col justify-center">
                        <p class="text-sm font-bold uppercase text-teal-700">
                            {{ __('rider.add_motorcycle') }}
                        </p>
                        <h1 class="mt-2 text-3xl font-black leading-tight text-slate-950 sm:text-4xl">
                            {{ __('rider.add_motorcycle_heading') }}
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                            {{ __('rider.add_motorcycle_intro') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('rider.motorcycles.store') }}" enctype="multipart/form-data" class="rounded-lg border border-slate-200 bg-white p-6 text-slate-950 shadow-sm sm:p-8">
                        @csrf

                        @if ($errors->any())
                            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                {{ __('rider.validation_error') }}
                            </div>
                        @endif

                        @include('riders.motorcycles._form', [
                            'submitLabel' => __('rider.save_motorcycle'),
                        ])
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
