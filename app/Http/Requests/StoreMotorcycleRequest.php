<?php

namespace App\Http\Requests;

use App\Models\Motorcycle;
use App\Models\MotorcycleBrand;
use App\Models\MotorcycleModel;
use App\Support\MotorcycleCatalog;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreMotorcycleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->rider !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(Motorcycle::allowedTypes())],
            'brand_id' => ['required', 'integer', Rule::exists('motorcycle_brands', 'id')->where('is_active', true)],
            'model_id' => ['required', 'integer', Rule::exists('motorcycle_models', 'id')->where('is_active', true)],
            'custom_brand' => ['nullable', 'string', 'max:255'],
            'custom_model' => ['nullable', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1950', 'max:'.(now()->year + 1)],
            'engine_cc' => ['required', 'numeric', 'min:1', 'max:3000'],
            'plate_number' => ['required', 'string', 'max:255', Rule::unique('motorcycles', 'plate_number')],
            'color' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', File::image()->max(5 * 1024)],
            'ownership_license_image' => ['nullable', File::image()->max(5 * 1024)],
            'motorcycle_registration_image' => ['nullable', File::image()->max(5 * 1024)],
        ];
    }

    protected function prepareForValidation(): void
    {
        app(MotorcycleCatalog::class)->sync();

        if ($this->has('plate_number')) {
            $this->merge([
                'plate_number' => strtoupper(trim((string) $this->input('plate_number'))),
            ]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $brand = $this->brand();
            $model = $this->model();

            if ($brand && $brand->name === 'Other' && trim((string) $this->input('custom_brand')) === '') {
                $validator->errors()->add('custom_brand', __('validation.required', ['attribute' => 'custom brand']));
            }

            if ($model && $model->name === 'Other' && trim((string) $this->input('custom_model')) === '') {
                $validator->errors()->add('custom_model', __('validation.required', ['attribute' => 'custom model']));
            }

            if ($brand && $model && $model->brand_id !== $brand->id) {
                $validator->errors()->add('model_id', __('validation.exists', ['attribute' => 'model']));
            }
        });
    }

    protected function brand(): ?MotorcycleBrand
    {
        $brandId = $this->integer('brand_id');

        return $brandId > 0
            ? MotorcycleBrand::query()->find($brandId)
            : null;
    }

    protected function model(): ?MotorcycleModel
    {
        $modelId = $this->integer('model_id');

        return $modelId > 0
            ? MotorcycleModel::query()->find($modelId)
            : null;
    }
}
