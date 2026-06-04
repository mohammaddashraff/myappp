<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\Product;
use App\Models\Service;
use App\Support\AccessRoles;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderMarketplaceController extends Controller
{
    use ResolvesRider;

    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $rider = $user?->rider;
        $isAdmin = $user?->hasAnyRole([AccessRoles::SUPER_ADMIN, AccessRoles::ADMIN]) === true;

        abort_unless($rider !== null || $isAdmin, 403);

        return view('riders.marketplace.index', [
            'rider' => $rider,
            'canUseRiderActions' => $rider !== null && ! $isAdmin,
            'cartCount' => $rider?->cartItems()->sum('quantity') ?? 0,
            'orderCount' => $rider?->orders()->count() ?? 0,
            'bookingCount' => $rider?->serviceBookings()->count() ?? 0,
            'requestCount' => $rider === null
                ? 0
                : $rider->roadsideRequests()->count()
                    + $rider->batteryReplacementRequests()->count()
                    + $rider->dealerInquiries()->count(),
            'productCount' => Product::query()->where('status', 'active')->count(),
            'serviceCount' => Service::query()->where('status', 'active')->count(),
        ]);
    }
}
