<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Driver dashboard | {{ config('app.name', 'Tayaran') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 font-sans text-white antialiased">
        <main class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.18),_transparent_34rem),linear-gradient(135deg,_#09090b,_#111827_52%,_#172554)] px-5 py-8 sm:px-6 lg:px-8">
            <nav class="mx-auto flex max-w-6xl items-center justify-between">
                <a href="{{ route('drivers.dashboard') }}" class="inline-flex items-center gap-3">
                    <span class="flex size-11 items-center justify-center rounded-2xl bg-teal-400 text-lg font-black text-zinc-950 shadow-lg shadow-teal-950/40">T</span>
                    <span>
                        <span class="block text-xl font-extrabold tracking-wide">Tayaran</span>
                        <span class="block text-sm text-teal-100/80">Driver dashboard</span>
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl border border-white/15 px-4 py-2 text-sm font-bold text-zinc-200 transition hover:bg-white/10 hover:text-white">
                        Sign out
                    </button>
                </form>
            </nav>

            <section class="mx-auto flex min-h-[calc(100vh-8rem)] max-w-4xl items-center justify-center py-12 text-center">
                <div>
                    <p class="inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-sm font-bold text-teal-100">
                        Driver workspace
                    </p>
                    <h1 class="mt-6 text-4xl font-black leading-tight sm:text-5xl">
                        Your driver dashboard is being prepared
                    </h1>
                    <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-zinc-300 sm:text-lg">
                        We are building the tools for live delivery requests, earnings, location status, and application updates. This workspace will be available here soon.
                    </p>
                </div>
            </section>
        </main>
    </body>
</html>
