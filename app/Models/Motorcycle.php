<?php

namespace App\Models;

use Database\Factories\MotorcycleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'rider_id',
    'brand_id',
    'model_id',
    'custom_brand',
    'custom_model',
    'nickname',
    'type',
    'brand',
    'model',
    'year',
    'engine_cc',
    'color',
    'image',
    'ownership_license_image',
    'motorcycle_registration_image',
    'owner_name',
    'plate_number',
    'chassis_number',
    'motor_number',
    'registration_expires_at',
    'is_primary',
])]
class Motorcycle extends Model
{
    /** @use HasFactory<MotorcycleFactory> */
    use HasFactory;

    public const OTHER_TYPE = 'other';

    /**
     * @return array<int, string>
     */
    public static function allowedTypes(): array
    {
        return array_keys(config('motorcycles.types', []));
    }

    /**
     * @return BelongsTo<Rider, $this>
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }

    /**
     * @return BelongsTo<MotorcycleBrand, $this>
     */
    public function brandRelation(): BelongsTo
    {
        return $this->belongsTo(MotorcycleBrand::class, 'brand_id');
    }

    /**
     * @return BelongsTo<MotorcycleModel, $this>
     */
    public function modelRelation(): BelongsTo
    {
        return $this->belongsTo(MotorcycleModel::class, 'model_id');
    }

    /**
     * @return HasMany<MotorcycleDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(MotorcycleDocument::class);
    }

    public function displayBrand(): string
    {
        return $this->custom_brand
            ?: $this->brandRelation?->name
            ?: ($this->brand ?? __('rider.not_recorded'));
    }

    public function displayModel(): string
    {
        return $this->custom_model
            ?: $this->modelRelation?->name
            ?: ($this->model ?? __('rider.not_recorded'));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'engine_cc' => 'integer',
            'registration_expires_at' => 'date',
            'is_primary' => 'boolean',
        ];
    }
}
