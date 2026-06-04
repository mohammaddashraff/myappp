<?php

namespace App\Providers;

use App\Models\User;
use App\Support\AccessRoles;
use App\Support\RoleRedirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ViewFactory::composer(['layouts.navigation', 'riders.partials.sidebar'], function (View $view): void {
            $user = Auth::user();

            if ($user === null) {
                return;
            }

            $user->loadMissing([
                'dealershipProfile',
                'deliveryPartnerProfile',
                'providerApplications',
                'rider',
                'roadsideProviderProfile',
                'sellerProfile',
                'serviceCenterProfile',
            ]);

            $rider = $user->rider;

            $view->with([
                'navCartCount' => $rider?->cartItems()->sum('quantity') ?? 0,
                'navWishlistCount' => $rider?->wishlistItems()->count() ?? 0,
                'navBusinessGroups' => $this->businessNavigationGroups($user),
                'navDashboardRoute' => RoleRedirector::routeNameFor($user),
                'navProviderStatusItems' => $this->providerStatusItems($user),
                'navIsAdmin' => $user->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN]),
            ]);
        });

        ViewFactory::composer('riders.marketplace.*', function (View $view): void {
            $user = Auth::user();

            if ($user === null) {
                return;
            }

            $isAdmin = $user->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN]);

            $view->with('canUseRiderActions', $user->rider !== null && ! $isAdmin);
        });
    }

    /**
     * @return array<int, array{label: string, links: array<int, array{label: string, route: string, active: string}>}>
     */
    protected function businessNavigationGroups(User $user): array
    {
        $groups = [];

        if ($user->sellerProfile?->isApproved()) {
            $groups[] = [
                'label' => __('rider.nav_seller'),
                'links' => [
                    ['label' => __('rider.nav_dashboard'), 'route' => 'seller.dashboard', 'active' => 'seller.dashboard'],
                    ['label' => __('rider.nav_my_products'), 'route' => 'seller.products.index', 'active' => 'seller.products.*'],
                    ['label' => __('rider.nav_seller_orders'), 'route' => 'seller.orders.index', 'active' => 'seller.orders.*'],
                    ['label' => __('rider.nav_seller_profile'), 'route' => 'seller.profile.edit', 'active' => 'seller.profile.*'],
                ],
            ];
        }

        if ($user->serviceCenterProfile?->isApproved()) {
            $groups[] = [
                'label' => __('rider.nav_service_center'),
                'links' => [
                    ['label' => __('rider.nav_dashboard'), 'route' => 'service-center.dashboard', 'active' => 'service-center.dashboard'],
                    ['label' => __('rider.nav_my_services'), 'route' => 'service-center.services.index', 'active' => 'service-center.services.*'],
                    ['label' => __('rider.nav_service_bookings'), 'route' => 'service-center.bookings.index', 'active' => 'service-center.bookings.*'],
                    ['label' => __('rider.nav_service_center_profile'), 'route' => 'service-center.profile.edit', 'active' => 'service-center.profile.*'],
                ],
            ];
        }

        if ($user->roadsideProviderProfile?->isApproved()) {
            $groups[] = [
                'label' => __('rider.nav_roadside_provider'),
                'links' => [
                    ['label' => __('rider.nav_dashboard'), 'route' => 'roadside-provider.dashboard', 'active' => 'roadside-provider.dashboard'],
                    ['label' => __('rider.nav_roadside_requests'), 'route' => 'roadside-provider.requests.index', 'active' => 'roadside-provider.requests.*'],
                    ['label' => __('rider.nav_roadside_profile'), 'route' => 'roadside-provider.profile.edit', 'active' => 'roadside-provider.profile.*'],
                ],
            ];
        }

        if ($user->deliveryPartnerProfile?->isApproved()) {
            $groups[] = [
                'label' => __('rider.nav_delivery_partner'),
                'links' => [
                    ['label' => __('rider.nav_dashboard'), 'route' => 'delivery-partner.dashboard', 'active' => 'delivery-partner.dashboard'],
                    ['label' => __('rider.nav_delivery_tasks'), 'route' => 'delivery-partner.tasks.index', 'active' => 'delivery-partner.tasks.*'],
                    ['label' => __('rider.nav_delivery_profile'), 'route' => 'delivery-partner.profile.edit', 'active' => 'delivery-partner.profile.*'],
                ],
            ];
        }

        if ($user->dealershipProfile?->isApproved()) {
            $groups[] = [
                'label' => __('rider.nav_dealership'),
                'links' => [
                    ['label' => __('rider.nav_dashboard'), 'route' => 'dealership.dashboard', 'active' => 'dealership.dashboard'],
                    ['label' => __('rider.nav_motorcycle_listings'), 'route' => 'dealership.listings.index', 'active' => 'dealership.listings.*'],
                    ['label' => __('rider.nav_dealer_inquiries'), 'route' => 'dealership.inquiries.index', 'active' => 'dealership.inquiries.*'],
                    ['label' => __('rider.nav_dealership_profile'), 'route' => 'dealership.profile.edit', 'active' => 'dealership.profile.*'],
                ],
            ];
        }

        return $groups;
    }

    /**
     * @return array<int, array{label: string, status: string, badge: string}>
     */
    protected function providerStatusItems(User $user): array
    {
        $items = [];

        foreach ($user->providerApplications->sortByDesc('created_at')->unique('requested_role') as $application) {
            if ($application->status !== AccessRoles::STATUS_APPROVED) {
                $items[] = [
                    'label' => str($application->requested_role)->headline()->toString(),
                    'status' => $application->status,
                    'badge' => match ($application->status) {
                        AccessRoles::STATUS_PENDING => __('rider.nav_pending_review'),
                        AccessRoles::STATUS_REJECTED => __('rider.nav_rejected'),
                        AccessRoles::STATUS_SUSPENDED => __('rider.nav_suspended'),
                        default => str($application->status)->headline()->toString(),
                    },
                ];
            }
        }

        foreach ([
            __('rider.nav_seller') => $user->sellerProfile,
            __('rider.nav_service_center') => $user->serviceCenterProfile,
            __('rider.nav_roadside_provider') => $user->roadsideProviderProfile,
            __('rider.nav_delivery_partner') => $user->deliveryPartnerProfile,
            __('rider.nav_dealership') => $user->dealershipProfile,
        ] as $label => $profile) {
            if ($profile?->status === AccessRoles::STATUS_SUSPENDED) {
                $items[] = [
                    'label' => $label,
                    'status' => AccessRoles::STATUS_SUSPENDED,
                    'badge' => __('rider.nav_suspended'),
                ];
            }
        }

        return $items;
    }
}
