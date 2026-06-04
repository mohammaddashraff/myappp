<?php

namespace App\Models;

use Database\Factories\RiderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'full_name',
    'date_of_birth',
    'current_address',
    'phone_number',
    'backup_phone_number',
    'emergency_contact_name',
    'emergency_contact_relationship',
    'emergency_contact_phone',
    'profile_completed_at',
])]
class Rider extends Model
{
    /** @use HasFactory<RiderFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Motorcycle, $this>
     */
    public function motorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class);
    }

    /**
     * @return HasMany<CartItem, $this>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasMany<RiderSavedAddress, $this>
     */
    public function savedAddresses(): HasMany
    {
        return $this->hasMany(RiderSavedAddress::class);
    }

    /**
     * @return HasMany<WishlistItem, $this>
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany<ServiceBooking, $this>
     */
    public function serviceBookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }

    /**
     * @return HasMany<RoadsideRequest, $this>
     */
    public function roadsideRequests(): HasMany
    {
        return $this->hasMany(RoadsideRequest::class);
    }

    /**
     * @return HasMany<BatteryReplacementRequest, $this>
     */
    public function batteryReplacementRequests(): HasMany
    {
        return $this->hasMany(BatteryReplacementRequest::class);
    }

    /**
     * @return HasMany<DealerInquiry, $this>
     */
    public function dealerInquiries(): HasMany
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
            'date_of_birth' => 'date',
            'profile_completed_at' => 'datetime',
        ];
    }
}
