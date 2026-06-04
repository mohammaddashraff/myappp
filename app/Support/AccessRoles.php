<?php

namespace App\Support;

class AccessRoles
{
    public const SUPER_ADMIN = 'super_admin';

    public const ADMIN = 'admin';

    public const RIDER = 'rider';

    public const SELLER = 'seller';

    public const SERVICE_CENTER = 'service_center';

    public const ROADSIDE_PROVIDER = 'roadside_provider';

    public const DELIVERY_PARTNER = 'delivery_partner';

    public const DEALERSHIP = 'dealership';

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_SUSPENDED = 'suspended';

    /**
     * @return array<int, string>
     */
    public static function roles(): array
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
            self::RIDER,
            self::SELLER,
            self::SERVICE_CENTER,
            self::ROADSIDE_PROVIDER,
            self::DELIVERY_PARTNER,
            self::DEALERSHIP,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function providerRoles(): array
    {
        return [
            self::SELLER,
            self::SERVICE_CENTER,
            self::ROADSIDE_PROVIDER,
            self::DELIVERY_PARTNER,
            self::DEALERSHIP,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function applicationStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_SUSPENDED,
        ];
    }

    public static function dashboardRouteFor(string $role): string
    {
        return match ($role) {
            self::SUPER_ADMIN, self::ADMIN => 'admin.dashboard',
            self::SELLER => 'seller.dashboard',
            self::SERVICE_CENTER => 'service-center.dashboard',
            self::ROADSIDE_PROVIDER => 'roadside-provider.dashboard',
            self::DELIVERY_PARTNER => 'delivery-partner.dashboard',
            self::DEALERSHIP => 'dealership.dashboard',
            default => 'rider.dashboard',
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
            self::SELLER,
            self::SERVICE_CENTER,
            self::ROADSIDE_PROVIDER,
            self::DELIVERY_PARTNER,
            self::DEALERSHIP,
            self::RIDER,
        ];
    }
}
