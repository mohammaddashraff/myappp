<?php

namespace App\Models;

use Database\Factories\MotorcycleBrandFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'country',
    'is_active',
])]
class MotorcycleBrand extends Model
{
    /** @use HasFactory<MotorcycleBrandFactory> */
    use HasFactory;

    /**
     * @return HasMany<MotorcycleModel, $this>
     */
    public function models(): HasMany
    {
        return $this->hasMany(MotorcycleModel::class, 'brand_id');
    }

    /**
     * @return HasMany<Motorcycle, $this>
     */
    public function motorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class, 'brand_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
