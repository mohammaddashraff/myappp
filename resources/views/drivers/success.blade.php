<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Application received | {{ config('app.name', 'Tayaran') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 font-sans text-white antialiased">
        <main class="flex min-h-screen items-center justify-center bg-[radial-gradient(circle_at_center,_rgba(20,184,166,0.18),_transparent_32rem),linear-gradient(135deg,_#09090b,_#111827_52%,_#172554)] px-5 py-10">
            <section class="w-full max-w-2xl rounded-3xl border border-white/10 bg-white p-8 text-center text-zinc-950 shadow-2xl shadow-black/30 sm:p-10">
                <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-teal-400 text-3xl font-black text-zinc-950">T</div>
                <p class="mt-6 text-sm font-bold uppercase tracking-wide text-teal-700">Tayaran</p>
                <h1 class="mt-2 text-3xl font-black sm:text-4xl">Application received</h1>
                <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-zinc-600">
                    Your driver signup has been saved and marked pending review.
                    @if (session('driver_application_id'))
                        Application #{{ session('driver_application_id') }}
                    @endif
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ route('drivers.signup.create', ['fresh' => 1]) }}" class="inline-flex justify-center rounded-xl bg-zinc-950 px-5 py-3 text-sm font-bold text-white transition hover:bg-zinc-800">
                        New application
                    </a>
                    <a href="/" class="inline-flex justify-center rounded-xl px-5 py-3 text-sm font-bold text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950">
                        Home
                    </a>
                </div>
            </section>
        </main>
    </body>
</html>
