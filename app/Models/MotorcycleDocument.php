<?php

namespace App\Models;

use Database\Factories\MotorcycleDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'motorcycle_id',
    'document_type',
    'title',
    'file_path',
    'issued_at',
    'expires_at',
    'reminder_at',
    'status',
    'notes',
])]
class MotorcycleDocument extends Model
{
    /** @use HasFactory<MotorcycleDocumentFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Motorcycle, $this>
     */
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'expires_at' => 'date',
            'reminder_at' => 'date',
        ];
    }
}
