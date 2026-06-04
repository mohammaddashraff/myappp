<?php

namespace App\Models;

use App\Support\AccessRoles;
use Database\Factories\DeliveryPartnerProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'full_name',
    'phone',
    'national_id',
    'license_number',
    'motorcycle_id',
    'status',
])]
class DeliveryPartnerProfile extends Model
{
    /** @use HasFactory<DeliveryPartnerProfileFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Motorcycle, $this>
     */
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    /**
     * @return HasMany<DeliveryTask, $this>
     */
    public function deliveryTasks(): HasMany
    {
        return $this->hasMany(DeliveryTask::class);
    }

    public function isApproved(): bool
    {
        return $this->status === AccessRoles::STATUS_APPROVED;
    }
}
