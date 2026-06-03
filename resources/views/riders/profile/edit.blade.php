@php
    $fieldValue = fn (string $name): mixed => old($name, data_get($rider, $name, ''));
    $dateOfBirth = old('date_of_birth', $rider?->date_of_birth?->toDateString());
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('rider.profile_page_title') }} | {{ __('rider.brand') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-950 antialiased">
        <main class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
            <section class="mx-auto grid max-w-7xl gap-6 md:grid-cols-[240px_minmax(0,1fr)] xl:grid-cols-[280px_minmax(0,1fr)]">
                @include('riders.partials.sidebar', ['active' => 'profile', 'showAddButton' => false])

                <div class="grid gap-6 py-2 lg:grid-cols-[0.82fr_1.18fr] lg:py-4">
                    <div class="flex flex-col justify-center">
                        <p class="text-sm font-bold uppercase text-teal-700">
                            {{ __('rider.profile_eyebrow') }}
                        </p>
                        <h1 class="mt-2 text-3xl font-black leading-tight text-slate-950 sm:text-4xl">
                            {{ __('rider.profile_heading') }}
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                            {{ __('rider.profile_intro') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('rider.profile.update') }}" class="rounded-lg border border-slate-200 bg-white p-6 text-slate-950 shadow-sm sm:p-8">
                        @csrf
                        @method('PATCH')

                        <div class="border-b border-slate-200 pb-6">
                            <p class="text-sm font-bold uppercase text-teal-700">{{ __('rider.basic_data') }}</p>
                            <h2 class="mt-1 text-3xl font-black text-slate-950">{{ __('rider.profile_eyebrow') }}</h2>
                        </div>

                        @if ($errors->any())
                            <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                {{ __('rider.validation_error') }}
                            </div>
                        @endif

                        @if (session('status') === 'rider-profile-updated')
                            <div class="mt-6 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-bold text-teal-800">
                                {{ __('rider.profile_updated') }}
                            </div>
                        @endif

                        <div class="mt-8 grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <x-input-label for="full_name" :value="__('rider.full_name')" />
                                <x-text-input id="full_name" name="full_name" type="text" class="mt-2 block w-full" :value="$fieldValue('full_name') ?: auth()->user()->name" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone_number" :value="__('rider.phone_number')" />
                                <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-2 block w-full" :value="$fieldValue('phone_number')" required autocomplete="tel" placeholder="+20 10 0000 0000" dir="ltr" />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="date_of_birth" :value="__('rider.date_of_birth')" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-2 block w-full" :value="$dateOfBirth" />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="current_address" :value="__('rider.current_address')" />
                                <textarea id="current_address" name="current_address" rows="4" required class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">{{ $fieldValue('current_address') }}</textarea>
                                <x-input-error :messages="$errors->get('current_address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="backup_phone_number" :value="__('rider.backup_phone_number')" />
                                <x-text-input id="backup_phone_number" name="backup_phone_number" type="tel" class="mt-2 block w-full" :value="$fieldValue('backup_phone_number')" placeholder="+20 11 0000 0000" dir="ltr" />
                                <x-input-error :messages="$errors->get('backup_phone_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="emergency_contact_phone" :value="__('rider.emergency_contact_phone')" />
                                <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="tel" class="mt-2 block w-full" :value="$fieldValue('emergency_contact_phone')" placeholder="+20 12 0000 0000" dir="ltr" />
                                <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="emergency_contact_name" :value="__('rider.emergency_contact_name')" />
                                <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-2 block w-full" :value="$fieldValue('emergency_contact_name')" />
                                <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="emergency_contact_relationship" :value="__('rider.emergency_contact_relationship')" />
                                <x-text-input id="emergency_contact_relationship" name="emergency_contact_relationship" type="text" class="mt-2 block w-full" :value="$fieldValue('emergency_contact_relationship')" :placeholder="__('rider.relationship_placeholder')" />
                                <x-input-error :messages="$errors->get('emergency_contact_relationship')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('rider.dashboard') }}" class="inline-flex justify-center rounded-md px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">
                                {{ __('rider.cancel') }}
                            </a>

                            <button type="submit" class="inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                {{ __('rider.save_profile') }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
