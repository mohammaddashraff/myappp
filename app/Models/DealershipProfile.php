<?php

namespace App\Models;

use App\Support\AccessRoles;
use Database\Factories\DealershipProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'dealership_name',
    'phone',
    'address',
    'city',
    'description',
    'logo',
    'status',
])]
class DealershipProfile extends Model
{
    /** @use HasFactory<DealershipProfileFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<DealerMotorcycle, $this>
     */
    public function motorcycleListings(): HasMany
    {
        return $this->hasMany(DealerMotorcycle::class);
    }

    public function isApproved(): bool
    {
        return $this->status === AccessRoles::STATUS_APPROVED;
    }
}
