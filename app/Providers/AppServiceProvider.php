<?php

namespace App\Providers;

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
        ViewFactory::composer('layouts.navigation', function (View $view): void {
            $user = Auth::user();

            if ($user === null) {
                return;
            }

            $user->loadMissing('subscription');

            $view->with([
                'navActiveAdsCount' => $user->publishedUnsoldAdsCount(),
                'navCanPublishAds' => $user->canPublishAds(),
                'navDashboardRoute' => RoleRedirector::routeNameFor($user),
                'navIsAdmin' => $user->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN]),
                'navSubscription' => $user->subscription,
            ]);
        });

        ViewFactory::composer(['dashboard', 'ads.*', 'seller-profiles.*', 'subscriptions.show'], function (View $view): void {
            $user = Auth::user();

            if ($user === null) {
                return;
            }

            $view->with([
                'viewerCanSeeSellerContact' => $user->canViewSellerContact(),
                'publishedAdsCount' => $user->publishedUnsoldAdsCount(),
                'publishedAdsLimit' => $user->activeSubscription()?->planLimit() ?? 0,
            ]);
        });
    }
}
