<?php

namespace App\Models;

use Database\Factories\AdInteractionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['ad_id', 'user_id', 'type'])]
class AdInteraction extends Model
{
    /** @use HasFactory<AdInteractionFactory> */
    use HasFactory;

    public const TYPE_VIEW = 'view';

    public const TYPE_PHONE_REVEAL = 'phone_reveal';

    /**
     * @return BelongsTo<Ad, $this>
     */
    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
