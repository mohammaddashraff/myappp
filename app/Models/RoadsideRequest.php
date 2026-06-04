<?php

namespace App\Models;

use Database\Factories\RoadsideRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'rider_id',
    'roadside_provider_profile_id',
    'request_number',
    'assistance_type',
    'motorcycle_id',
    'location',
    'description',
    'contact_phone',
    'status',
])]
class RoadsideRequest extends Model
{
    /** @use HasFactory<RoadsideRequestFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_ON_THE_WAY = 'on_the_way';

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
            self::STATUS_ON_THE_WAY,
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
            self::STATUS_PENDING => [self::STATUS_ACCEPTED, self::STATUS_CANCELLED],
            self::STATUS_ACCEPTED => [self::STATUS_ON_THE_WAY, self::STATUS_CANCELLED],
            self::STATUS_ON_THE_WAY => [self::STATUS_COMPLETED, self::STATUS_CANCELLED],
            self::STATUS_COMPLETED => [],
            self::STATUS_CANCELLED => [],
        ];
    }

    public static function nextNumber(): string
    {
        return 'RSR-'.now()->format('Ymd').'-'.str_pad((string) ((self::query()->count() % 9999) + 1), 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return BelongsTo<Rider, $this>
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }

    /**
     * @return BelongsTo<RoadsideProviderProfile, $this>
     */
    public function roadsideProviderProfile(): BelongsTo
    {
        return $this->belongsTo(RoadsideProviderProfile::class);
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
