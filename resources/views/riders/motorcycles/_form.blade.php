@php
    $selectedBrandId = (string) old('brand_id', $motorcycle->brand_id ?? $motorcycle->brandRelation?->id ?? '');
    $selectedModelId = (string) old('model_id', $motorcycle->model_id ?? $motorcycle->modelRelation?->id ?? '');
    $initialBrand = collect($brands)->firstWhere('id', (int) $selectedBrandId);
    $initialModels = $initialBrand['models'] ?? [];
@endphp

<div
    x-data="{
        brands: @js($brands),
        selectedBrandId: '{{ $selectedBrandId }}',
        selectedModelId: '{{ $selectedModelId }}',
        get selectedBrand() {
            return this.brands.find((brand) => String(brand.id) === String(this.selectedBrandId)) ?? null;
        },
        get availableModels() {
            return this.selectedBrand ? this.selectedBrand.models : [];
        },
        get showCustomBrand() {
            return this.selectedBrand?.name === 'Other';
        },
        get showCustomModel() {
            return this.availableModels.find((model) => String(model.id) === String(this.selectedModelId))?.name === 'Other';
        },
        renderModelOptions() {
            const placeholder = `<option value=''>{{ __('rider.model_label') }}</option>`;
            const options = this.availableModels.map((model) => {
                const selected = String(model.id) === String(this.selectedModelId) ? 'selected' : '';

                return `<option value='${String(model.id)}' ${selected}>${model.name}</option>`;
            }).join('');

            this.$refs.modelSelect.innerHTML = placeholder + options;
        },
        syncModelState() {
            if (! this.availableModels.some((model) => String(model.id) === String(this.selectedModelId))) {
                this.selectedModelId = '';
            }

            this.renderModelOptions();
        },
    }"
    x-init="syncModelState()"
    class="space-y-6"
>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <x-input-label for="type" :value="__('rider.motorcycle_type')" />
            <select id="type" name="type" class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                <option value="">{{ __('rider.motorcycle_type') }}</option>
                @foreach (config('motorcycles.types', []) as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $motorcycle->type) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('type')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="brand_id" :value="__('rider.brand_label')" />
            <select id="brand_id" name="brand_id" x-model="selectedBrandId" @change="syncModelState()" class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                <option value="">{{ __('rider.brand_label') }}</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('brand_id')" class="mt-2" />
        </div>

        <div x-show="showCustomBrand" x-cloak>
            <x-input-label for="custom_brand" :value="__('rider.custom_brand')" />
            <x-text-input id="custom_brand" name="custom_brand" type="text" class="mt-2 block w-full" :value="old('custom_brand', $motorcycle->custom_brand)" />
            <x-input-error :messages="$errors->get('custom_brand')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="model_id" :value="__('rider.model_label')" />
            <select id="model_id" name="model_id" x-ref="modelSelect" x-model="selectedModelId" class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                <option value="">{{ __('rider.model_label') }}</option>
                @foreach ($initialModels as $model)
                    <option value="{{ $model['id'] }}" @selected((string) $model['id'] === $selectedModelId)>{{ $model['name'] }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('model_id')" class="mt-2" />
        </div>

        <div x-show="showCustomModel" x-cloak>
            <x-input-label for="custom_model" :value="__('rider.custom_model')" />
            <x-text-input id="custom_model" name="custom_model" type="text" class="mt-2 block w-full" :value="old('custom_model', $motorcycle->custom_model)" />
            <x-input-error :messages="$errors->get('custom_model')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="year" :value="__('rider.manufacturing_year')" />
            <x-text-input id="year" name="year" type="number" min="1950" :max="now()->year + 1" class="mt-2 block w-full" :value="old('year', $motorcycle->year)" required />
            <x-input-error :messages="$errors->get('year')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="engine_cc" :value="__('rider.engine_cc')" />
            <x-text-input id="engine_cc" name="engine_cc" type="number" min="1" class="mt-2 block w-full" :value="old('engine_cc', $motorcycle->engine_cc)" required />
            <x-input-error :messages="$errors->get('engine_cc')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="plate_number" :value="__('rider.plate_number')" />
            <x-text-input id="plate_number" name="plate_number" type="text" class="mt-2 block w-full" :value="old('plate_number', $motorcycle->plate_number)" required />
            <x-input-error :messages="$errors->get('plate_number')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="color" :value="__('rider.color')" />
            <x-text-input id="color" name="color" type="text" class="mt-2 block w-full" :value="old('color', $motorcycle->color)" />
            <x-input-error :messages="$errors->get('color')" class="mt-2" />
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        @foreach ([
            'image' => 'motorcycle_image',
            'ownership_license_image' => 'ownership_license_image',
            'motorcycle_registration_image' => 'motorcycle_registration_image',
        ] as $field => $labelKey)
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <x-input-label :for="$field" :value="__('rider.'.$labelKey)" />
                <input id="{{ $field }}" name="{{ $field }}" type="file" accept="image/*" class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-white file:px-4 file:py-2 file:font-bold file:text-slate-700 hover:file:bg-teal-50">
                <p class="mt-2 text-xs text-slate-500">{{ __('rider.upload_hint') }}</p>
                <x-input-error :messages="$errors->get($field)" class="mt-2" />

                @if ($motorcycle->{$field})
                    <div class="mt-4">
                        <p class="text-xs font-bold uppercase text-slate-500">{{ __('rider.current_upload') }}</p>
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($motorcycle->{$field}) }}" alt="{{ __('rider.'.$labelKey) }}" class="mt-2 h-32 w-full rounded-md object-cover">
                        <p class="mt-2 text-xs text-slate-500">{{ __('rider.replace_optional_upload') }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('rider.garage') }}" class="inline-flex justify-center rounded-md px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">
            {{ __('rider.back_to_garage') }}
        </a>

        <button type="submit" class="inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
            {{ $submitLabel }}
        </button>
    </div>
</div>
