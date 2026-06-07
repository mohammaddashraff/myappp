@extends('layouts.app')

@section('title', ($ad->exists ? __('app.edit_ad') : __('app.create_ad')).' | '.__('app.brand'))

@section('content')
    @php
        $remainingSlots = max(0, $publishedAdsLimit - $publishedAdsCount);
    @endphp

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="moto-section p-6 sm:p-8">
            <p class="text-sm font-black text-teal-700">{{ __('app.ad_editor') }}</p>
            <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-4xl font-black text-slate-950">{{ $ad->exists ? __('app.edit_ad') : __('app.create_ad') }}</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">{{ __('app.ad_editor_intro') }}</p>
                </div>
                <a href="{{ route('ads.my') }}" class="button-muted">{{ __('app.my_ads') }}</a>
            </div>
        </section>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_0.38fr]">
            <form method="POST" action="{{ $ad->exists ? route('ads.update', $ad) : route('ads.store') }}" enctype="multipart/form-data" class="moto-section p-6" x-data="{
                description: @js(old('description', $ad->description ?? '')),
                selectedImages: [],
                updateSelectedImages(event) {
                    this.selectedImages = Array.from(event.target.files || []).map((file) => ({
                        name: file.name,
                        size: `${(file.size / 1024 / 1024).toFixed(1)} MB`,
                        preview: URL.createObjectURL(file),
                    }));
                },
            }">
                @csrf
                @if ($ad->exists)
                    @method('PATCH')
                @endif

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <x-input-label for="title" :value="__('app.title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full rounded-xl" :value="old('title', $ad->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="description" :value="__('app.description')" />
                        <textarea id="description" name="description" rows="4" maxlength="255" x-model="description" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>{{ old('description', $ad->description) }}</textarea>
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-xs font-bold text-slate-500">
                            <span>{{ __('app.description_limit_help') }}</span>
                            <span x-text="`${description.length}/255`" class="rounded-lg bg-slate-100 px-3 py-1 text-slate-700"></span>
                        </div>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="category" :value="__('app.category')" />
                        <select id="category" name="category" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                            @foreach (\App\Models\Ad::categories() as $category)
                                <option value="{{ $category }}" @selected(old('category', $ad->category) === $category)>{{ __('app.'.$category) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="price" :value="__('app.price')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full rounded-xl" :value="old('price', $ad->price)" required />
                    </div>

                    <div>
                        <x-input-label for="location" :value="__('app.location')" />
                        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full rounded-xl" :value="old('location', $ad->location)" required />
                    </div>

                    <div>
                        <x-input-label for="condition" :value="__('app.condition')" />
                        <select id="condition" name="condition" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                            <option value="new" @selected(old('condition', $ad->condition) === 'new')>{{ __('app.new') }}</option>
                            <option value="used" @selected(old('condition', $ad->condition) === 'used')>{{ __('app.used') }}</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="contact_phone" :value="__('app.contact_phone')" />
                        <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full rounded-xl" :value="old('contact_phone', $ad->contact_phone)" required dir="ltr" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="images" :value="__('app.ad_images')" />
                        <label for="images" class="mt-1 flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center transition hover:border-yellow-300 hover:bg-yellow-50/50">
                            <span class="text-sm font-black text-teal-700">{{ __('app.upload_images') }}</span>
                            <span class="mt-2 max-w-lg text-sm leading-6 text-slate-600">{{ __('app.ad_images_help') }}</span>
                            <span class="mt-3 rounded-lg bg-white px-4 py-2 text-xs font-black text-slate-700 shadow-sm">{{ __('app.choose_images') }}</span>
                        </label>
                        <input id="images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple class="mt-4 block w-full cursor-pointer rounded-lg border border-slate-300 bg-white text-sm font-bold text-slate-700 shadow-sm file:me-4 file:cursor-pointer file:border-0 file:bg-slate-950 file:px-4 file:py-3 file:text-sm file:font-black file:text-white hover:file:bg-slate-800" x-on:change="updateSelectedImages($event)">
                        <p class="mt-2 text-xs font-bold text-slate-500">{{ __('app.image_upload_rules') }}</p>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                        <x-input-error :messages="$errors->get('images.*')" class="mt-2" />

                        <div x-show="selectedImages.length > 0" x-cloak class="mt-4 rounded-lg border border-teal-200 bg-teal-50 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-black text-teal-950">{{ __('app.selected_images') }}</p>
                                <p class="text-xs font-bold text-teal-800" x-text="`${selectedImages.length} / 6`"></p>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                <template x-for="image in selectedImages" :key="image.name">
                                    <div class="overflow-hidden rounded-lg border border-white bg-white shadow-sm">
                                        <div class="aspect-square bg-slate-100">
                                            <img :src="image.preview" :alt="image.name" class="h-full w-full object-cover">
                                        </div>
                                        <div class="p-3">
                                            <p class="truncate text-xs font-black text-slate-950" x-text="image.name"></p>
                                            <p class="mt-1 text-xs font-bold text-slate-500" x-text="image.size"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        @if ($ad->exists && count($ad->imageUrls()) > 0)
                            <div class="mt-4 rounded-lg border border-slate-200 bg-white p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="text-sm font-black text-slate-950">{{ __('app.current_images') }}</p>
                                    <p class="text-xs font-bold text-slate-500">{{ __('app.replace_images_hint') }}</p>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                    @foreach ($ad->imageUrls() as $imageUrl)
                                        <div class="aspect-square overflow-hidden rounded-2xl bg-slate-100">
                                            <img src="{{ $imageUrl }}" alt="{{ $ad->title }}" class="h-full w-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('app.status')" />
                        <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                            <option value="{{ \App\Models\Ad::STATUS_DRAFT }}" @selected(old('status', $ad->status) === \App\Models\Ad::STATUS_DRAFT)>{{ __('app.draft') }}</option>
                            <option value="{{ \App\Models\Ad::STATUS_PUBLISHED }}" @selected(old('status', $ad->status) === \App\Models\Ad::STATUS_PUBLISHED)>{{ __('app.publish') }}</option>
                        </select>
                        <p class="mt-2 text-xs font-bold text-slate-500">{{ __('app.save_as_draft_hint') }}</p>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="submit" class="button-brand">{{ $ad->exists ? __('app.save_changes') : __('app.publish_ad') }}</button>
                    <a href="{{ route('ads.my') }}" class="button-muted">{{ __('app.cancel') }}</a>
                </div>
            </form>

            <aside class="space-y-4">
                <section class="rounded-xl border border-slate-200 bg-slate-950 p-5 text-white shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-yellow-300">{{ __('app.publishing_rules') }}</p>
                    <h2 class="mt-3 text-2xl font-black">{{ __('app.ad_slots_used', ['used' => $publishedAdsCount, 'limit' => $publishedAdsLimit ?: 0]) }}</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-300">{{ __('app.publishing_rules_body') }}</p>
                    <div class="mt-5 rounded-lg bg-white/10 p-4">
                        <p class="text-xs font-black uppercase text-slate-300">{{ __('app.available_slots_label') }}</p>
                        <p class="mt-1 text-4xl font-black">{{ $remainingSlots }}</p>
                    </div>
                </section>

                <section class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                    <p class="text-sm font-black text-amber-950">{{ __('app.contact_visibility') }}</p>
                    <p class="mt-2 text-sm leading-6 text-amber-800">{{ __('app.contact_visibility_body') }}</p>
                </section>
            </aside>
        </div>
    </div>
@endsection
