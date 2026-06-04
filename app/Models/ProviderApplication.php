<?php

namespace App\Models;

use App\Support\AccessRoles;
use Database\Factories\ProviderApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'requested_role',
    'business_name',
    'display_name',
    'phone',
    'address',
    'city',
    'description',
    'documents',
    'status',
    'admin_notes',
    'reviewed_by',
    'reviewed_at',
])]
class ProviderApplication extends Model
{
    /** @use HasFactory<ProviderApplicationFactory> */
    use HasFactory;

    public const STATUS_PENDING = AccessRoles::STATUS_PENDING;

    public const STATUS_APPROVED = AccessRoles::STATUS_APPROVED;

    public const STATUS_REJECTED = AccessRoles::STATUS_REJECTED;

    public const STATUS_SUSPENDED = AccessRoles::STATUS_SUSPENDED;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === AccessRoles::STATUS_PENDING;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [
            AccessRoles::STATUS_PENDING,
            AccessRoles::STATUS_APPROVED,
            AccessRoles::STATUS_SUSPENDED,
        ], true);
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->where('status', AccessRoles::STATUS_PENDING);
    }

    #[Scope]
    protected function approved(Builder $query): void
    {
        $query->where('status', AccessRoles::STATUS_APPROVED);
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereIn('status', [
            AccessRoles::STATUS_PENDING,
            AccessRoles::STATUS_APPROVED,
            AccessRoles::STATUS_SUSPENDED,
        ]);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'documents' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }
}
