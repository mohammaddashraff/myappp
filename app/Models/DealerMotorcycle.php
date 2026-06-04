<?php

namespace App\Models;

use Database\Factories\DealerMotorcycleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'dealer_id',
    'dealership_profile_id',
    'brand',
    'model',
    'year',
    'engine_cc',
    'condition',
    'price',
    'installment_available',
    'installment_options',
    'description',
    'image',
    'images',
    'status',
])]
class DealerMotorcycle extends Model
{
    /** @use HasFactory<DealerMotorcycleFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Dealer, $this>
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    /**
     * @return BelongsTo<DealershipProfile, $this>
     */
    public function dealershipProfile(): BelongsTo
    {
        return $this->belongsTo(DealershipProfile::class);
    }

    /**
     * @return HasMany<DealerInquiry, $this>
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(DealerInquiry::class);
    }

    public function fullName(): string
    {
        return "{$this->brand} {$this->model} {$this->year}";
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
            'price' => 'decimal:2',
            'installment_available' => 'boolean',
            'images' => 'array',
        ];
    }
}
