@php
    $fieldValue = fn (string $name): mixed => old($name, data_get($rider, $name, ''));
    $dateOfBirth = old('date_of_birth', $rider?->date_of_birth?->toDateString());
    $addressInputClass = 'rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500';
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

                <div class="grid gap-5 py-2 lg:py-4" dir="ltr">
                    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-bold uppercase text-teal-700">{{ __('rider.profile_eyebrow') }}</p>
                        <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <h1 class="text-3xl font-black text-slate-950">{{ __('rider.profile_heading') }}</h1>
                                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                                    Manage account access, rider details, saved addresses, wishlist items, and recent activity from one place.
                                </p>
                            </div>
                            <a href="{{ route('rider.dashboard') }}" class="inline-flex w-fit justify-center rounded-md border border-slate-200 bg-white px-4 py-2.5 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                                Back to dashboard
                            </a>
                        </div>
                    </section>

                    @if ($errors->any() || $errors->updatePassword->any())
                        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            {{ __('rider.validation_error') }}
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-bold text-teal-800">
                            @switch(session('status'))
                                @case('rider-profile-updated')
                                    {{ __('rider.profile_updated') }}
                                    @break

                                @case('rider-password-updated')
                                    Password updated.
                                    @break

                                @case('rider-address-saved')
                                    Saved address added.
                                    @break

                                @case('rider-address-updated')
                                    Saved address updated.
                                    @break

                                @case('rider-address-deleted')
                                    Saved address removed.
                                    @break

                                @case('wishlist-item-saved')
                                    Item saved to wishlist.
                                    @break

                                @case('wishlist-item-removed')
                                    Item removed from wishlist.
                                    @break

                                @default
                                    Saved.
                            @endswitch
                        </div>
                    @endif

                    <section class="grid gap-5 xl:grid-cols-[minmax(0,1.25fr)_minmax(320px,0.75fr)]">
                        <form method="POST" action="{{ route('rider.profile.update') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            @csrf
                            @method('PATCH')

                            <div class="border-b border-slate-200 pb-5">
                                <p class="text-sm font-bold uppercase text-teal-700">{{ __('rider.basic_data') }}</p>
                                <h2 class="mt-1 text-2xl font-black text-slate-950">Profile and account</h2>
                            </div>

                            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <x-input-label for="full_name" :value="__('rider.full_name')" />
                                    <x-text-input id="full_name" name="full_name" type="text" class="mt-2 block w-full" :value="$fieldValue('full_name') ?: $user->name" required autofocus autocomplete="name" />
                                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input-label for="email" value="Email" />
                                    <x-text-input id="email" name="email" type="email" class="mt-2 block w-full" :value="old('email', $user->email)" required autocomplete="username" dir="ltr" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                                    <textarea id="current_address" name="current_address" rows="3" required class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">{{ $fieldValue('current_address') }}</textarea>
                                    <p class="mt-2 text-xs font-semibold text-slate-500">This is kept as your profile fallback address. Add structured saved addresses below for checkout.</p>
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

                            <div class="mt-7 flex justify-end border-t border-slate-200 pt-5">
                                <button type="submit" class="inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                    {{ __('rider.save_profile') }}
                                </button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('rider.profile.password.update') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:self-start">
                            @csrf
                            @method('PATCH')

                            <p class="text-sm font-bold uppercase text-teal-700">Security</p>
                            <h2 class="mt-1 text-2xl font-black text-slate-950">Update password</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Use your current password before setting a new one.</p>

                            <div class="mt-6 grid gap-4">
                                <div>
                                    <x-input-label for="current_password" value="Current password" />
                                    <x-text-input id="current_password" name="current_password" type="password" class="mt-2 block w-full" autocomplete="current-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password" value="New password" />
                                    <x-text-input id="password" name="password" type="password" class="mt-2 block w-full" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" value="Confirm password" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>

                            <button type="submit" class="mt-6 inline-flex w-full justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-slate-800">
                                Save password
                            </button>
                        </form>
                    </section>

                    <section id="addresses" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p class="text-sm font-bold uppercase text-teal-700">Saved data</p>
                                <h2 class="mt-1 text-2xl font-black text-slate-950">Saved addresses</h2>
                                <p class="mt-2 text-sm text-slate-600">Use these structured addresses during checkout.</p>
                            </div>
                            <span class="text-sm font-black text-slate-500">{{ $savedAddresses->count() }} saved</span>
                        </div>

                        <form method="POST" action="{{ route('rider.profile.addresses.store') }}" class="mt-6 rounded-lg border border-slate-200 bg-slate-50 p-4">
                            @csrf
                            <h3 class="text-lg font-black text-slate-950">Add new address</h3>
                            <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                                <input name="label" value="{{ old('label') }}" placeholder="Label, e.g. Home" class="{{ $addressInputClass }}" required>
                                <input name="recipient_name" value="{{ old('recipient_name') }}" placeholder="Recipient name" class="{{ $addressInputClass }}">
                                <input name="phone" value="{{ old('phone') }}" placeholder="Phone" class="{{ $addressInputClass }}" dir="ltr">
                                <input name="city" value="{{ old('city') }}" placeholder="City" class="{{ $addressInputClass }}" required>
                                <input name="area" value="{{ old('area') }}" placeholder="Area / district" class="{{ $addressInputClass }}" required>
                                <input name="street" value="{{ old('street') }}" placeholder="Street name" class="{{ $addressInputClass }} xl:col-span-2" required>
                                <input name="building" value="{{ old('building') }}" placeholder="Building number" class="{{ $addressInputClass }}" required>
                                <input name="floor" value="{{ old('floor') }}" placeholder="Floor" class="{{ $addressInputClass }}" required>
                                <input name="apartment" value="{{ old('apartment') }}" placeholder="Apartment number" class="{{ $addressInputClass }}" required>
                                <input name="landmark" value="{{ old('landmark') }}" placeholder="Landmark" class="{{ $addressInputClass }}">
                                <input name="notes" value="{{ old('notes') }}" placeholder="Delivery notes" class="{{ $addressInputClass }} md:col-span-2 xl:col-span-3">
                                <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700">
                                    <input type="checkbox" name="is_default" value="1" class="rounded border-slate-300 text-teal-600">
                                    Default
                                </label>
                            </div>
                            <button type="submit" class="mt-4 inline-flex justify-center rounded-md bg-slate-950 px-5 py-2.5 text-sm font-black text-white transition hover:bg-slate-800">
                                Add address
                            </button>
                        </form>

                        @if ($savedAddresses->isEmpty())
                            <div class="mt-5 rounded-lg border border-dashed border-slate-300 bg-white p-6 text-center">
                                <h3 class="text-lg font-black text-slate-950">No saved addresses yet</h3>
                                <p class="mt-2 text-sm text-slate-500">Add your home, work, or garage address for faster checkout.</p>
                            </div>
                        @else
                            <div class="mt-5 grid gap-4">
                                @foreach ($savedAddresses as $address)
                                    <article class="rounded-lg border border-slate-200 bg-white p-4">
                                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h3 class="text-lg font-black text-slate-950">{{ $address->label }}</h3>
                                                    @if ($address->is_default)
                                                        <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-black text-teal-700">Default</span>
                                                    @endif
                                                </div>
                                                <p class="mt-1 text-sm leading-6 text-slate-600">{{ $address->formattedAddress() }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('rider.profile.addresses.destroy', $address) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-rose-200 px-4 py-2 text-sm font-black text-rose-700 transition hover:bg-rose-50">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>

                                        <form method="POST" action="{{ route('rider.profile.addresses.update', $address) }}" class="mt-4 grid gap-3 border-t border-slate-200 pt-4 md:grid-cols-2 xl:grid-cols-4">
                                            @csrf
                                            @method('PATCH')
                                            <input name="label" value="{{ old('label', $address->label) }}" placeholder="Label" class="{{ $addressInputClass }}" required>
                                            <input name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" placeholder="Recipient name" class="{{ $addressInputClass }}">
                                            <input name="phone" value="{{ old('phone', $address->phone) }}" placeholder="Phone" class="{{ $addressInputClass }}" dir="ltr">
                                            <input name="city" value="{{ old('city', $address->city) }}" placeholder="City" class="{{ $addressInputClass }}" required>
                                            <input name="area" value="{{ old('area', $address->area) }}" placeholder="Area / district" class="{{ $addressInputClass }}" required>
                                            <input name="street" value="{{ old('street', $address->street) }}" placeholder="Street name" class="{{ $addressInputClass }} xl:col-span-2" required>
                                            <input name="building" value="{{ old('building', $address->building) }}" placeholder="Building number" class="{{ $addressInputClass }}" required>
                                            <input name="floor" value="{{ old('floor', $address->floor) }}" placeholder="Floor" class="{{ $addressInputClass }}" required>
                                            <input name="apartment" value="{{ old('apartment', $address->apartment) }}" placeholder="Apartment number" class="{{ $addressInputClass }}" required>
                                            <input name="landmark" value="{{ old('landmark', $address->landmark) }}" placeholder="Landmark" class="{{ $addressInputClass }}">
                                            <input name="notes" value="{{ old('notes', $address->notes) }}" placeholder="Delivery notes" class="{{ $addressInputClass }} md:col-span-2">
                                            <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700">
                                                <input type="checkbox" name="is_default" value="1" @checked($address->is_default) class="rounded border-slate-300 text-teal-600">
                                                Default
                                            </label>
                                            <button type="submit" class="rounded-md bg-slate-950 px-4 py-2.5 text-sm font-black text-white transition hover:bg-slate-800">
                                                Save address
                                            </button>
                                        </form>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section class="grid gap-5 xl:grid-cols-2">
                        <div id="wishlist" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-end justify-between gap-3 border-b border-slate-200 pb-5">
                                <div>
                                    <p class="text-sm font-bold uppercase text-teal-700">Wishlist</p>
                                    <h2 class="mt-1 text-2xl font-black text-slate-950">Saved items</h2>
                                </div>
                                <a href="{{ route('rider.products.accessories') }}" class="text-sm font-black text-teal-700">Browse products</a>
                            </div>

                            @if ($wishlistItems->isEmpty())
                                <div class="mt-5 rounded-lg border border-dashed border-slate-300 p-6 text-center">
                                    <h3 class="text-lg font-black text-slate-950">No wishlist items yet</h3>
                                    <p class="mt-2 text-sm text-slate-500">Save accessories, spare parts, or batteries while browsing.</p>
                                </div>
                            @else
                                <div class="mt-5 grid gap-3">
                                    @foreach ($wishlistItems as $wishlistItem)
                                        @php($product = $wishlistItem->product)
                                        @php($productImageUrl = $product?->imageUrl())
                                        <article class="grid gap-3 rounded-lg border border-slate-200 p-3 sm:grid-cols-[82px_minmax(0,1fr)]">
                                            @if ($productImageUrl)
                                                <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" class="aspect-square w-full rounded-md object-cover">
                                            @else
                                                <div class="flex aspect-square w-full items-center justify-center rounded-md bg-slate-100 text-xs font-black text-slate-400">Image</div>
                                            @endif
                                            <div>
                                                <p class="text-xs font-black uppercase text-teal-700">{{ $product?->typeLabel() ?? 'Product' }}</p>
                                                <h3 class="mt-1 font-black text-slate-950">{{ $product?->name ?? 'Unavailable product' }}</h3>
                                                <p class="mt-1 text-sm font-bold text-slate-600">EGP {{ number_format((float) ($product?->price ?? 0)) }}</p>
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @if ($product)
                                                        <a href="{{ route('rider.products.show', $product) }}" class="rounded-md border border-slate-200 px-3 py-2 text-sm font-black text-slate-700 transition hover:bg-slate-50">View</a>
                                                    @endif
                                                    <form method="POST" action="{{ route('rider.wishlist.destroy', $wishlistItem) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-md border border-rose-200 px-3 py-2 text-sm font-black text-rose-700 transition hover:bg-rose-50">Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="border-b border-slate-200 pb-5">
                                <p class="text-sm font-bold uppercase text-teal-700">History</p>
                                <h2 class="mt-1 text-2xl font-black text-slate-950">Recent activity</h2>
                            </div>

                            @if ($historyItems->isEmpty())
                                <div class="mt-5 rounded-lg border border-dashed border-slate-300 p-6 text-center">
                                    <h3 class="text-lg font-black text-slate-950">No history yet</h3>
                                    <p class="mt-2 text-sm text-slate-500">Orders, bookings, and requests will appear here.</p>
                                </div>
                            @else
                                <div class="mt-5 grid gap-3">
                                    @foreach ($historyItems as $historyItem)
                                        <a href="{{ $historyItem['url'] }}" class="block rounded-lg border border-slate-200 p-4 transition hover:border-slate-300 hover:bg-slate-50">
                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                                <div>
                                                    <p class="text-xs font-black uppercase text-teal-700">{{ $historyItem['label'] }}</p>
                                                    <h3 class="mt-1 font-black text-slate-950">{{ $historyItem['number'] }}</h3>
                                                    <p class="mt-1 text-sm text-slate-600">{{ $historyItem['description'] }}</p>
                                                </div>
                                                <div class="text-left sm:text-right">
                                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-700">{{ str($historyItem['status'])->headline() }}</span>
                                                    <p class="mt-2 text-xs font-bold text-slate-400">{{ $historyItem['date']?->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </section>
        </main>
    </body>
</html>
