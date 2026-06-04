<?php

namespace App\Models;

use App\Support\AccessRoles;
use Database\Factories\ServiceCenterProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'center_name',
    'phone',
    'address',
    'city',
    'description',
    'working_hours',
    'status',
])]
class ServiceCenterProfile extends Model
{
    /** @use HasFactory<ServiceCenterProfileFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Service, $this>
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function isApproved(): bool
    {
        return $this->status === AccessRoles::STATUS_APPROVED;
    }
}
