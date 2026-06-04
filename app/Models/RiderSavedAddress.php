<?php

namespace App\Models;

use Database\Factories\RiderSavedAddressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'rider_id',
    'label',
    'recipient_name',
    'phone',
    'city',
    'area',
    'street',
    'building',
    'floor',
    'apartment',
    'landmark',
    'notes',
    'is_default',
])]
class RiderSavedAddress extends Model
{
    /** @use HasFactory<RiderSavedAddressFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Rider, $this>
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }

    public function formattedAddress(): string
    {
        $parts = [
            'Apartment '.$this->apartment,
            'Floor '.$this->floor,
            'Building '.$this->building,
            $this->street,
            $this->area,
            $this->city,
        ];

        if (filled($this->landmark)) {
            $parts[] = 'Landmark: '.$this->landmark;
        }

        if (filled($this->notes)) {
            $parts[] = 'Notes: '.$this->notes;
        }

        return collect($parts)
            ->filter(fn (?string $part): bool => filled($part))
            ->join(', ');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }
}
