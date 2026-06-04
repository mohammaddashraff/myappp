<?php

namespace App\Models;

use App\Support\AccessRoles;
use Database\Factories\RoadsideProviderProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'provider_name',
    'phone',
    'address',
    'city',
    'coverage_area',
    'status',
])]
class RoadsideProviderProfile extends Model
{
    /** @use HasFactory<RoadsideProviderProfileFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<RoadsideRequest, $this>
     */
    public function requests(): HasMany
    {
        return $this->hasMany(RoadsideRequest::class);
    }

    public function isApproved(): bool
    {
        return $this->status === AccessRoles::STATUS_APPROVED;
    }
}
