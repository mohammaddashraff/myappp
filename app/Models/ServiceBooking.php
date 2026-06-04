<?php

namespace App\Models;

use Database\Factories\ServiceBookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'rider_id',
    'service_id',
    'booking_number',
    'motorcycle_id',
    'booking_date',
    'preferred_time',
    'location_option',
    'notes',
    'contact_phone',
    'estimated_price',
    'status',
])]
class ServiceBooking extends Model
{
    /** @use HasFactory<ServiceBookingFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_COMPLETED,
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
            self::STATUS_PENDING => [self::STATUS_ACCEPTED, self::STATUS_REJECTED, self::STATUS_CANCELLED],
            self::STATUS_ACCEPTED => [self::STATUS_COMPLETED, self::STATUS_CANCELLED],
            self::STATUS_REJECTED => [],
            self::STATUS_COMPLETED => [],
            self::STATUS_CANCELLED => [],
        ];
    }

    public static function nextNumber(): string
    {
        return 'BKG-'.now()->format('Ymd').'-'.str_pad((string) ((self::query()->count() % 9999) + 1), 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return BelongsTo<Rider, $this>
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }

    /**
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * @return BelongsTo<Motorcycle, $this>
     */
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function statusLabel(): string
    {
        return str($this->status)->replace('_', ' ')->title()->toString();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'estimated_price' => 'decimal:2',
        ];
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->where('status', self::STATUS_PENDING);
    }

    #[Scope]
    protected function completed(Builder $query): void
    {
        $query->where('status', self::STATUS_COMPLETED);
    }
}
