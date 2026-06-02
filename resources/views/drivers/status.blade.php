<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Application status | {{ config('app.name', 'Tayaran') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 font-sans text-white antialiased">
        <main class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.18),_transparent_34rem),linear-gradient(135deg,_#09090b,_#111827_52%,_#172554)] px-5 py-8 sm:px-6 lg:px-8">
            <section class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-5xl items-center">
                <div class="w-full overflow-hidden rounded-3xl border border-white/10 bg-white text-zinc-950 shadow-2xl shadow-black/30">
                    <div class="grid gap-0 lg:grid-cols-[0.9fr_1.1fr]">
                        <aside class="bg-zinc-950 p-8 text-white sm:p-10">
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                                <span class="flex size-11 items-center justify-center rounded-2xl bg-teal-400 text-lg font-black text-zinc-950 shadow-lg shadow-teal-950/40">T</span>
                                <span>
                                    <span class="block text-xl font-extrabold tracking-wide">Tayaran</span>
                                    <span class="block text-sm text-teal-100/80">Driver onboarding</span>
                                </span>
                            </a>

                            <div class="mt-16 max-w-sm">
                                <p class="inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-sm font-bold text-teal-100">
                                    Pending review
                                </p>
                                <h1 class="mt-5 text-4xl font-black leading-tight sm:text-5xl">Your application is being reviewed</h1>
                                <p class="mt-5 text-base leading-7 text-zinc-300">
                                    The operations team is checking your identity, license, vehicle, and safety documents.
                                </p>
                            </div>
                        </aside>

                        <div class="p-6 sm:p-8 lg:p-10">
                            <div class="flex flex-col gap-4 border-b border-zinc-200 pb-6 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-sm font-bold uppercase tracking-wide text-teal-700">Application #{{ $driver->id }}</p>
                                    <h2 class="mt-1 text-2xl font-black text-zinc-950">{{ $driver->legal_name }}</h2>
                                    <p class="mt-2 text-sm text-zinc-500">
                                        Submitted {{ $driver->submitted_at?->format('F j, Y') ?? 'recently' }}
                                    </p>
                                </div>

                                <span class="inline-flex w-fit rounded-full bg-amber-100 px-4 py-2 text-sm font-black text-amber-800">
                                    Pending
                                </span>
                            </div>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                    <p class="text-xs font-black uppercase tracking-wide text-zinc-500">Phone</p>
                                    <p class="mt-2 text-base font-bold text-zinc-950">{{ $driver->phone_number }}</p>
                                </div>

                                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                    <p class="text-xs font-black uppercase tracking-wide text-zinc-500">Plate number</p>
                                    <p class="mt-2 text-base font-bold text-zinc-950">{{ $driver->plate_number }}</p>
                                </div>

                                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                    <p class="text-xs font-black uppercase tracking-wide text-zinc-500">Vehicle owner</p>
                                    <p class="mt-2 text-base font-bold text-zinc-950">{{ $driver->vehicle_owner_name }}</p>
                                </div>

                                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                    <p class="text-xs font-black uppercase tracking-wide text-zinc-500">Emergency contact</p>
                                    <p class="mt-2 text-base font-bold text-zinc-950">{{ $driver->emergency_contact_name }}</p>
                                </div>
                            </div>

                            <div class="mt-8 rounded-2xl bg-zinc-950 p-5 text-white">
                                <p class="text-sm font-black uppercase tracking-wide text-teal-200">Next step</p>
                                <p class="mt-3 text-sm leading-6 text-zinc-300">
                                    Keep your phone available. Once an admin approves the application, this status page will stop showing and sign-in will take you to the home page.
                                </p>
                            </div>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <a href="{{ route('home') }}" class="inline-flex justify-center rounded-xl px-5 py-3 text-sm font-bold text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950">
                                    Home
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-zinc-950 px-5 py-3 text-sm font-black text-white transition hover:bg-zinc-800 sm:w-auto">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
