<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Support\AccessRoles;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN, AccessRoles::SELLER]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return $this->canManagePlatformProducts($user) || $this->ownsProduct($user, $product);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->canManagePlatformProducts($user) || $user->sellerProfile?->isApproved() === true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $this->canManagePlatformProducts($user) || $this->ownsProduct($user, $product);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $this->canManagePlatformProducts($user) || $this->ownsProduct($user, $product);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return $this->canManagePlatformProducts($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->hasRole(AccessRoles::SUPER_ADMIN);
    }

    protected function canManagePlatformProducts(User $user): bool
    {
        return $user->hasRole(AccessRoles::SUPER_ADMIN) || $user->can('manage products');
    }

    protected function ownsProduct(User $user, Product $product): bool
    {
        return $user->sellerProfile?->isApproved() === true
            && $product->seller_profile_id !== null
            && $product->seller_profile_id === $user->sellerProfile->id;
    }
}
