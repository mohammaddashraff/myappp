<?php

namespace App\Models;

use Database\Factories\DealerInquiryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'rider_id',
    'dealer_id',
    'dealer_motorcycle_id',
    'inquiry_number',
    'rider_name',
    'phone',
    'message',
    'preferred_contact_method',
    'status',
])]
class DealerInquiry extends Model
{
    /** @use HasFactory<DealerInquiryFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_CLOSED = 'closed';

    public const STATUS_CANCELLED = 'cancelled';

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONTACTED,
            self::STATUS_CLOSED,
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
            self::STATUS_PENDING => [self::STATUS_CONTACTED, self::STATUS_CANCELLED],
            self::STATUS_CONTACTED => [self::STATUS_CLOSED, self::STATUS_CANCELLED],
            self::STATUS_CLOSED => [],
            self::STATUS_CANCELLED => [],
        ];
    }

    public static function nextNumber(): string
    {
        return 'INQ-'.now()->format('Ymd').'-'.str_pad((string) ((self::query()->count() % 9999) + 1), 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return BelongsTo<Rider, $this>
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }

    /**
     * @return BelongsTo<Dealer, $this>
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    /**
     * @return BelongsTo<DealerMotorcycle, $this>
     */
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(DealerMotorcycle::class, 'dealer_motorcycle_id');
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
        $query->where('status', self::STATUS_CLOSED);
    }
}
