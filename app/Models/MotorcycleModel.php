<?php

namespace App\Models;

use Database\Factories\MotorcycleModelFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'brand_id',
    'name',
    'type',
    'default_engine_cc',
    'is_active',
])]
class MotorcycleModel extends Model
{
    /** @use HasFactory<MotorcycleModelFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<MotorcycleBrand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(MotorcycleBrand::class, 'brand_id');
    }

    /**
     * @return HasMany<Motorcycle, $this>
     */
    public function motorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class, 'model_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default_engine_cc' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
