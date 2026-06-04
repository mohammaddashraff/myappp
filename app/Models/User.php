<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * @return HasOne<Driver, $this>
     */
    public function driverApplication(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    /**
     * @return HasOne<Rider, $this>
     */
    public function rider(): HasOne
    {
        return $this->hasOne(Rider::class);
    }

    /**
     * @return HasMany<ProviderApplication, $this>
     */
    public function providerApplications(): HasMany
    {
        return $this->hasMany(ProviderApplication::class);
    }

    /**
     * @return HasOne<SellerProfile, $this>
     */
    public function sellerProfile(): HasOne
    {
        return $this->hasOne(SellerProfile::class);
    }

    /**
     * @return HasOne<ServiceCenterProfile, $this>
     */
    public function serviceCenterProfile(): HasOne
    {
        return $this->hasOne(ServiceCenterProfile::class);
    }

    /**
     * @return HasOne<RoadsideProviderProfile, $this>
     */
    public function roadsideProviderProfile(): HasOne
    {
        return $this->hasOne(RoadsideProviderProfile::class);
    }

    /**
     * @return HasOne<DeliveryPartnerProfile, $this>
     */
    public function deliveryPartnerProfile(): HasOne
    {
        return $this->hasOne(DeliveryPartnerProfile::class);
    }

    /**
     * @return HasOne<DealershipProfile, $this>
     */
    public function dealershipProfile(): HasOne
    {
        return $this->hasOne(DealershipProfile::class);
    }

    /**
     * @return HasManyThrough<Motorcycle, Rider, $this>
     */
    public function motorcycles(): HasManyThrough
    {
        return $this->hasManyThrough(Motorcycle::class, Rider::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
