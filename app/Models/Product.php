<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'seller_profile_id',
    'type',
    'name',
    'description',
    'category',
    'brand',
    'price',
    'stock_quantity',
    'condition',
    'image',
    'location',
    'seller_name',
    'delivery_available',
    'pickup_available',
    'installation_available',
    'compatible_motorcycle_types',
    'compatible_motorcycle_brands',
    'compatible_motorcycle_models',
    'estimated_delivery_time',
    'warranty_info',
    'return_policy',
    'voltage',
    'capacity',
    'status',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public const TYPE_ACCESSORY = 'accessory';

    public const TYPE_SPARE_PART = 'spare_part';

    public const TYPE_BATTERY = 'battery';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_OUT_OF_STOCK = 'out_of_stock';

    public const STATUS_PENDING_APPROVAL = 'pending_approval';

    /**
     * @return array<int, string>
     */
    public static function types(): array
    {
        return [
            self::TYPE_ACCESSORY,
            self::TYPE_SPARE_PART,
            self::TYPE_BATTERY,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
            self::STATUS_OUT_OF_STOCK,
            self::STATUS_PENDING_APPROVAL,
        ];
    }

    /**
     * @return HasMany<CartItem, $this>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return BelongsTo<SellerProfile, $this>
     */
    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class);
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0 && $this->status === self::STATUS_ACTIVE;
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_ACCESSORY => 'Accessory',
            self::TYPE_SPARE_PART => 'Spare Part',
            self::TYPE_BATTERY => 'Battery',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function imageUrl(): ?string
    {
        if (blank($this->image)) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://', '/'])) {
            return $this->image;
        }

        return Storage::url($this->image);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'delivery_available' => 'boolean',
            'pickup_available' => 'boolean',
            'installation_available' => 'boolean',
            'compatible_motorcycle_types' => 'array',
            'compatible_motorcycle_brands' => 'array',
            'compatible_motorcycle_models' => 'array',
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', self::STATUS_ACTIVE);
    }
}
