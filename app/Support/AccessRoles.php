<?php

namespace App\Support;

class AccessRoles
{
    public const SUPER_ADMIN = 'super_admin';

    public const ADMIN = 'admin';

    /**
     * @return array<int, string>
     */
    public static function roles(): array
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
        ];
    }

    public static function dashboardRouteFor(string $role): string
    {
        return match ($role) {
            self::SUPER_ADMIN, self::ADMIN => 'admin.dashboard',
            default => 'dashboard',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function redirectPriority(): array
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
        ];
    }
}
