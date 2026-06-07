<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\AccessRoles;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * @return HasOne<Subscription, $this>
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * @return HasMany<Ad, $this>
     */
    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    /**
     * @return HasMany<UserReview, $this>
     */
    public function receivedReviews(): HasMany
    {
        return $this->hasMany(UserReview::class, 'reviewed_user_id');
    }

    /**
     * @return HasMany<UserReview, $this>
     */
    public function givenReviews(): HasMany
    {
        return $this->hasMany(UserReview::class, 'reviewer_id');
    }

    /**
     * @return HasMany<AdInteraction, $this>
     */
    public function adInteractions(): HasMany
    {
        return $this->hasMany(AdInteraction::class);
    }

    public function activeSubscription(): ?Subscription
    {
        $subscription = $this->relationLoaded('subscription')
            ? $this->getRelation('subscription')
            : $this->subscription;

        return $subscription?->isActive() ? $subscription : null;
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }

    public function publishedUnsoldAdsCount(): int
    {
        return $this->ads()
            ->where('status', Ad::STATUS_PUBLISHED)
            ->whereNull('sold_at')
            ->count();
    }

    public function canPublishAds(): bool
    {
        $subscription = $this->activeSubscription();

        if ($subscription === null) {
            return false;
        }

        return $this->publishedUnsoldAdsCount() < $subscription->planLimit();
    }

    public function canViewSellerContact(): bool
    {
        return $this->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN])
            || $this->hasActiveSubscription();
    }

    public function averageRating(): ?float
    {
        $average = $this->relationLoaded('receivedReviews')
            ? $this->receivedReviews->avg('rating')
            : $this->receivedReviews()->avg('rating');

        return $average === null ? null : round((float) $average, 1);
    }

    public function reviewsCount(): int
    {
        return $this->relationLoaded('receivedReviews')
            ? $this->receivedReviews->count()
            : $this->receivedReviews()->count();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
