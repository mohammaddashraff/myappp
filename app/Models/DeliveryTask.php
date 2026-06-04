<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'delivery_partner_profile_id',
    'order_id',
    'pickup_address',
    'dropoff_address',
    'phone',
    'status',
])]
class DeliveryTask extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_ASSIGNED = 'assigned';

    public const STATUS_PICKED_UP = 'picked_up';

    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ASSIGNED,
            self::STATUS_PICKED_UP,
            self::STATUS_OUT_FOR_DELIVERY,
            self::STATUS_DELIVERED,
            self::STATUS_FAILED,
            self::STATUS_CANCELLED,
        ];
    }

    public function canTransitionTo(string $status): bool
    {
        if ($this->status === $status) {
            return true;
        }

        return in_array($status, self::transitions()[$this->status] ?? [], true);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function transitions(): array
    {
        return [
            self::STATUS_PENDING => [self::STATUS_ASSIGNED, self::STATUS_CANCELLED],
            self::STATUS_ASSIGNED => [self::STATUS_PICKED_UP, self::STATUS_CANCELLED],
            self::STATUS_PICKED_UP => [self::STATUS_OUT_FOR_DELIVERY, self::STATUS_FAILED, self::STATUS_CANCELLED],
            self::STATUS_OUT_FOR_DELIVERY => [self::STATUS_DELIVERED, self::STATUS_FAILED],
            self::STATUS_DELIVERED => [],
            self::STATUS_FAILED => [],
            self::STATUS_CANCELLED => [],
        ];
    }

    /**
     * @return BelongsTo<DeliveryPartnerProfile, $this>
     */
    public function deliveryPartnerProfile(): BelongsTo
    {
        return $this->belongsTo(DeliveryPartnerProfile::class);
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function statusLabel(): string
    {
        return str($this->status)->replace('_', ' ')->title()->toString();
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->where('status', self::STATUS_PENDING);
    }

    #[Scope]
    protected function completed(Builder $query): void
    {
        $query->where('status', self::STATUS_DELIVERED);
    }
}
