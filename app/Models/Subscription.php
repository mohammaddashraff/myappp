<?php

namespace App\Models;

use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'plan',
    'status',
    'starts_at',
    'ends_at',
    'activated_at',
    'activated_by',
    'payment_gateway',
    'payment_reference',
])]
class Subscription extends Model
{
    /** @use HasFactory<SubscriptionFactory> */
    use HasFactory;

    public const PLAN_INDIVIDUAL = 'individual';

    public const PLAN_BUSINESS = 'business';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_EXPIRED = 'expired';

    /**
     * @return array<int, string>
     */
    public static function plans(): array
    {
        return [
            self::PLAN_INDIVIDUAL,
            self::PLAN_BUSINESS,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_INACTIVE,
            self::STATUS_ACTIVE,
            self::STATUS_EXPIRED,
        ];
    }

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
    public function activatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    public function isActive(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        return $this->ends_at === null || $this->ends_at->isFuture();
    }

    public function planLimit(): int
    {
        return match ($this->plan) {
            self::PLAN_BUSINESS => 5,
            default => 1,
        };
    }

    public function planLabel(): string
    {
        return (string) __('app.'.$this->plan);
    }

    public function statusLabel(): string
    {
        return (string) __('app.'.$this->status);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'activated_at' => 'datetime',
        ];
    }
}
