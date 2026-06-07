<?php

namespace App\Support;

use App\Models\User;

class RoleRedirector
{
    public static function routeNameFor(User $user): string
    {
        foreach (AccessRoles::redirectPriority() as $role) {
            if ($user->hasRole($role)) {
                return AccessRoles::dashboardRouteFor($role);
            }
        }

        return 'dashboard';
    }
}
