<?php

namespace App\Models;

use Database\Factories\RiderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'full_name',
    'date_of_birth',
    'current_address',
    'phone_number',
    'backup_phone_number',
    'emergency_contact_name',
    'emergency_contact_relationship',
    'emergency_contact_phone',
    'profile_completed_at',
])]
class Rider extends Model
{
    /** @use HasFactory<RiderFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Motorcycle, $this>
     */
    public function motorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'profile_completed_at' => 'datetime',
        ];
    }
}
