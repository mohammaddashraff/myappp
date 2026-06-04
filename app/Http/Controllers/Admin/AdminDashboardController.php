<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatteryReplacementRequest;
use App\Models\DealerInquiry;
use App\Models\DealershipProfile;
use App\Models\DeliveryPartnerProfile;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProviderApplication;
use App\Models\Rider;
use App\Models\RoadsideProviderProfile;
use App\Models\RoadsideRequest;
use App\Models\SellerProfile;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\ServiceCenterProfile;
use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalUsers' => User::query()->count(),
            'totalRiders' => Rider::query()->count(),
            'pendingApplications' => ProviderApplication::query()
                ->pending()
                ->count(),
            'sellersCount' => SellerProfile::query()->count(),
            'serviceCentersCount' => ServiceCenterProfile::query()->count(),
            'roadsideProvidersCount' => RoadsideProviderProfile::query()->count(),
            'deliveryPartnersCount' => DeliveryPartnerProfile::query()->count(),
            'dealershipsCount' => DealershipProfile::query()->count(),
            'totalProducts' => Product::query()->count(),
            'totalOrders' => Order::query()->count(),
            'totalServices' => Service::query()->count(),
            'totalServiceBookings' => ServiceBooking::query()->count(),
            'totalRoadsideRequests' => RoadsideRequest::query()->count(),
            'totalBatteryRequests' => BatteryReplacementRequest::query()->count(),
            'totalDealerInquiries' => DealerInquiry::query()->count(),
            'approvedProvidersCount' => SellerProfile::query()->where('status', AccessRoles::STATUS_APPROVED)->count()
                + ServiceCenterProfile::query()->where('status', AccessRoles::STATUS_APPROVED)->count()
                + RoadsideProviderProfile::query()->where('status', AccessRoles::STATUS_APPROVED)->count()
                + DeliveryPartnerProfile::query()->where('status', AccessRoles::STATUS_APPROVED)->count()
                + DealershipProfile::query()->where('status', AccessRoles::STATUS_APPROVED)->count(),
            'suspendedProvidersCount' => SellerProfile::query()->where('status', AccessRoles::STATUS_SUSPENDED)->count()
                + ServiceCenterProfile::query()->where('status', AccessRoles::STATUS_SUSPENDED)->count()
                + RoadsideProviderProfile::query()->where('status', AccessRoles::STATUS_SUSPENDED)->count()
                + DeliveryPartnerProfile::query()->where('status', AccessRoles::STATUS_SUSPENDED)->count()
                + DealershipProfile::query()->where('status', AccessRoles::STATUS_SUSPENDED)->count(),
            'recentApplications' => ProviderApplication::query()->with('user')->latest()->limit(6)->get(),
        ]);
    }
}
