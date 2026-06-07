<?php

namespace App\Models;

use Database\Factories\AdFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'user_id',
    'title',
    'description',
    'category',
    'price',
    'location',
    'condition',
    'contact_phone',
    'images',
    'status',
    'sold_at',
])]
class Ad extends Model
{
    /** @use HasFactory<AdFactory> */
    use HasFactory;

    public const CATEGORY_MOTORCYCLE = 'motorcycle';

    public const CATEGORY_PART = 'part';

    public const CATEGORY_ACCESSORY = 'accessory';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_SOLD = 'sold';

    /**
     * @return array<int, string>
     */
    public static function categories(): array
    {
        return [
            self::CATEGORY_MOTORCYCLE,
            self::CATEGORY_PART,
            self::CATEGORY_ACCESSORY,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PUBLISHED,
            self::STATUS_SOLD,
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
     * @return HasMany<AdInteraction, $this>
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(AdInteraction::class);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isSold(): bool
    {
        return $this->status === self::STATUS_SOLD;
    }

    public function categoryLabel(): string
    {
        return (string) __('app.'.$this->category);
    }

    public function conditionLabel(): string
    {
        return (string) __('app.'.$this->condition);
    }

    public function statusLabel(): string
    {
        return (string) __('app.'.$this->status);
    }

    /**
     * @return array<int, string>
     */
    public function imageUrls(): array
    {
        return collect($this->images ?? [])
            ->filter()
            ->map(fn (string $image): string => str($image)->startsWith(['http://', 'https://'])
                ? $image
                : Storage::url($image))
            ->values()
            ->all();
    }

    public function firstImageUrl(): ?string
    {
        return $this->imageUrls()[0] ?? null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'images' => 'array',
            'sold_at' => 'datetime',
        ];
    }
}
