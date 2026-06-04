<?php

namespace App\Http\Controllers;

use App\Models\DealerInquiry;
use App\Models\DeliveryTask;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RoadsideRequest;
use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleDashboardController extends Controller
{
    public function seller(Request $request): View
    {
        $profile = $request->user()->sellerProfile;

        return view('role-dashboards.seller', [
            'productsCount' => Product::query()->whereBelongsTo($profile)->count(),
            'activeProductsCount' => Product::query()->whereBelongsTo($profile)->active()->count(),
            'lowStockProductsCount' => Product::query()->whereBelongsTo($profile)->where('stock_quantity', '<=', 3)->count(),
            'ordersCount' => OrderItem::query()->whereHas('product', fn ($query) => $query->whereBelongsTo($profile))->distinct('order_id')->count('order_id'),
            'pendingOrdersCount' => OrderItem::query()
                ->whereHas('product', fn ($query) => $query->whereBelongsTo($profile))
                ->whereHas('order', fn ($query) => $query->pending())
                ->distinct('order_id')
                ->count('order_id'),
            'completedOrdersCount' => OrderItem::query()
                ->whereHas('product', fn ($query) => $query->whereBelongsTo($profile))
                ->whereHas('order', fn ($query) => $query->completed())
                ->distinct('order_id')
                ->count('order_id'),
        ]);
    }

    public function serviceCenter(Request $request): View
    {
        $profile = $request->user()->serviceCenterProfile;

        return view('role-dashboards.service-center', [
            'servicesCount' => Service::query()->whereBelongsTo($profile)->count(),
            'pendingBookingsCount' => ServiceBooking::query()->whereHas('service', fn ($query) => $query->whereBelongsTo($profile))->pending()->count(),
            'acceptedBookingsCount' => ServiceBooking::query()->whereHas('service', fn ($query) => $query->whereBelongsTo($profile))->where('status', ServiceBooking::STATUS_ACCEPTED)->count(),
            'completedBookingsCount' => ServiceBooking::query()->whereHas('service', fn ($query) => $query->whereBelongsTo($profile))->completed()->count(),
        ]);
    }

    public function roadsideProvider(Request $request): View
    {
        $profile = $request->user()->roadsideProviderProfile;

        return view('role-dashboards.roadside-provider', [
            'availableRequestsCount' => RoadsideRequest::query()->whereNull('roadside_provider_profile_id')->pending()->count(),
            'activeRequestsCount' => RoadsideRequest::query()->whereBelongsTo($profile)->whereIn('status', [RoadsideRequest::STATUS_ACCEPTED, RoadsideRequest::STATUS_ON_THE_WAY])->count(),
            'onTheWayRequestsCount' => RoadsideRequest::query()->whereBelongsTo($profile)->where('status', RoadsideRequest::STATUS_ON_THE_WAY)->count(),
            'completedRequestsCount' => RoadsideRequest::query()->whereBelongsTo($profile)->completed()->count(),
        ]);
    }

    public function deliveryPartner(Request $request): View
    {
        $profile = $request->user()->deliveryPartnerProfile;

        return view('role-dashboards.delivery-partner', [
            'availableDeliveriesCount' => DeliveryTask::query()->whereNull('delivery_partner_profile_id')->pending()->count(),
            'pendingDeliveriesCount' => DeliveryTask::query()->whereBelongsTo($profile)->where('status', DeliveryTask::STATUS_ASSIGNED)->count(),
            'activeDeliveriesCount' => DeliveryTask::query()->whereBelongsTo($profile)->whereIn('status', [DeliveryTask::STATUS_ASSIGNED, DeliveryTask::STATUS_PICKED_UP, DeliveryTask::STATUS_OUT_FOR_DELIVERY])->count(),
            'completedDeliveriesCount' => DeliveryTask::query()->whereBelongsTo($profile)->completed()->count(),
            'deliveryHistoryCount' => DeliveryTask::query()->whereBelongsTo($profile)->whereIn('status', [DeliveryTask::STATUS_DELIVERED, DeliveryTask::STATUS_FAILED, DeliveryTask::STATUS_CANCELLED])->count(),
        ]);
    }

    public function dealership(Request $request): View
    {
        $profile = $request->user()->dealershipProfile;

        return view('role-dashboards.dealership', [
            'listingsCount' => $profile?->motorcycleListings()->count() ?? 0,
            'activeListingsCount' => $profile?->motorcycleListings()->where('status', 'active')->count() ?? 0,
            'soldListingsCount' => $profile?->motorcycleListings()->where('status', 'sold')->count() ?? 0,
            'inquiriesCount' => DealerInquiry::query()
                ->whereHas('motorcycle', fn ($query) => $query->whereBelongsTo($profile))
                ->count(),
            'newInquiriesCount' => DealerInquiry::query()
                ->whereHas('motorcycle', fn ($query) => $query->whereBelongsTo($profile))
                ->pending()
                ->count(),
        ]);
    }
}
