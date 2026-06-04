<?php

namespace App\Support;

use App\Models\User;

class RoleRedirector
{
    public static function routeNameFor(User $user): string
    {
        foreach (AccessRoles::redirectPriority() as $role) {
            if ($user->hasRole($role) && self::roleIsActive($user, $role)) {
                return AccessRoles::dashboardRouteFor($role);
            }
        }

        return AccessRoles::dashboardRouteFor(AccessRoles::RIDER);
    }

    public static function hasInactiveProviderRole(User $user): bool
    {
        foreach (AccessRoles::providerRoles() as $role) {
            if ($user->hasRole($role) && ! self::roleIsActive($user, $role)) {
                return true;
            }
        }

        return false;
    }

    protected static function roleIsActive(User $user, string $role): bool
    {
        return match ($role) {
            AccessRoles::SELLER => $user->sellerProfile?->isApproved() === true,
            AccessRoles::SERVICE_CENTER => $user->serviceCenterProfile?->isApproved() === true,
            AccessRoles::ROADSIDE_PROVIDER => $user->roadsideProviderProfile?->isApproved() === true,
            AccessRoles::DELIVERY_PARTNER => $user->deliveryPartnerProfile?->isApproved() === true,
            AccessRoles::DEALERSHIP => $user->dealershipProfile?->isApproved() === true,
            default => true,
        };
    }
}
