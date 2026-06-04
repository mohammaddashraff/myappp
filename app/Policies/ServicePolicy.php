<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use App\Support\AccessRoles;

class ServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN, AccessRoles::SERVICE_CENTER]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Service $service): bool
    {
        return $this->canManagePlatformServices($user) || $this->ownsService($user, $service);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->canManagePlatformServices($user) || $user->serviceCenterProfile?->isApproved() === true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Service $service): bool
    {
        return $this->canManagePlatformServices($user) || $this->ownsService($user, $service);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Service $service): bool
    {
        return $this->canManagePlatformServices($user) || $this->ownsService($user, $service);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Service $service): bool
    {
        return $this->canManagePlatformServices($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Service $service): bool
    {
        return $user->hasRole(AccessRoles::SUPER_ADMIN);
    }

    protected function canManagePlatformServices(User $user): bool
    {
        return $user->hasRole(AccessRoles::SUPER_ADMIN) || $user->can('manage services');
    }

    protected function ownsService(User $user, Service $service): bool
    {
        return $user->serviceCenterProfile?->isApproved() === true
            && $service->service_center_profile_id !== null
            && $service->service_center_profile_id === $user->serviceCenterProfile->id;
    }
}
