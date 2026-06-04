<?php

namespace App\Policies;

use App\Models\DealerMotorcycle;
use App\Models\User;
use App\Support\AccessRoles;

class DealerMotorcyclePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN, AccessRoles::DEALERSHIP]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DealerMotorcycle $dealerMotorcycle): bool
    {
        return $this->canManagePlatformDealerships($user) || $this->ownsListing($user, $dealerMotorcycle);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->canManagePlatformDealerships($user) || $user->dealershipProfile?->isApproved() === true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DealerMotorcycle $dealerMotorcycle): bool
    {
        return $this->canManagePlatformDealerships($user) || $this->ownsListing($user, $dealerMotorcycle);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DealerMotorcycle $dealerMotorcycle): bool
    {
        return $this->canManagePlatformDealerships($user) || $this->ownsListing($user, $dealerMotorcycle);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DealerMotorcycle $dealerMotorcycle): bool
    {
        return $this->canManagePlatformDealerships($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DealerMotorcycle $dealerMotorcycle): bool
    {
        return $user->hasRole(AccessRoles::SUPER_ADMIN);
    }

    protected function canManagePlatformDealerships(User $user): bool
    {
        return $user->hasRole(AccessRoles::SUPER_ADMIN) || $user->can('manage dealerships');
    }

    protected function ownsListing(User $user, DealerMotorcycle $dealerMotorcycle): bool
    {
        return $user->dealershipProfile?->isApproved() === true
            && $dealerMotorcycle->dealership_profile_id !== null
            && $dealerMotorcycle->dealership_profile_id === $user->dealershipProfile->id;
    }
}
