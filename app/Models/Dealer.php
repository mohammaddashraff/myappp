<?php

namespace App\Models;

use Database\Factories\DealerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'location',
    'brands_available',
    'phone',
    'rating',
    'status',
])]
class Dealer extends Model
{
    /** @use HasFactory<DealerFactory> */
    use HasFactory;

    /**
     * @return HasMany<DealerMotorcycle, $this>
     */
    public function motorcycles(): HasMany
    {
        return $this->hasMany(DealerMotorcycle::class);
    }

    /**
     * @return HasMany<DealerInquiry, $this>
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(DealerInquiry::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'brands_available' => 'array',
            'rating' => 'decimal:2',
        ];
    }
}
