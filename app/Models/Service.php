<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'service_center_profile_id',
    'name',
    'category',
    'description',
    'estimated_price',
    'estimated_duration',
    'service_center_name',
    'location',
    'rating',
    'working_hours',
    'pickup_available',
    'available_today',
    'motorcycle_types',
    'notes',
    'status',
])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    /**
     * @return HasMany<ServiceBooking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }

    /**
     * @return BelongsTo<ServiceCenterProfile, $this>
     */
    public function serviceCenterProfile(): BelongsTo
    {
        return $this->belongsTo(ServiceCenterProfile::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estimated_price' => 'decimal:2',
            'rating' => 'decimal:2',
            'pickup_available' => 'boolean',
            'available_today' => 'boolean',
            'motorcycle_types' => 'array',
        ];
    }
}
