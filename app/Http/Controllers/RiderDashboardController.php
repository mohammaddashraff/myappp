<?php

namespace App\Http\Controllers;

use App\Models\BatteryReplacementRequest;
use App\Models\Order;
use App\Models\RoadsideRequest;
use App\Models\ServiceBooking;
use App\Support\RoleRedirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderDashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $dashboardRoute = RoleRedirector::routeNameFor($request->user());

        if ($dashboardRoute !== 'rider.dashboard') {
            return redirect()->route($dashboardRoute);
        }

        $user = $request->user()->load([
            'driverApplication',
            'rider.motorcycles.documents',
        ]);

        return view('riders.dashboard', [
            'driverApplication' => $user->driverApplication,
            'rider' => $user->rider,
            'recentOrders' => $user->rider?->orders()->with('items')->latest()->limit(3)->get() ?? collect(),
            'activeServiceBookingsCount' => $user->rider?->serviceBookings()->whereNotIn('status', [
                ServiceBooking::STATUS_COMPLETED,
                ServiceBooking::STATUS_CANCELLED,
                ServiceBooking::STATUS_REJECTED,
            ])->count() ?? 0,
            'activeRoadsideRequestsCount' => $user->rider?->roadsideRequests()->whereNotIn('status', [
                RoadsideRequest::STATUS_COMPLETED,
                RoadsideRequest::STATUS_CANCELLED,
            ])->count() ?? 0,
            'activeBatteryRequestsCount' => $user->rider?->batteryReplacementRequests()->whereNotIn('status', [
                BatteryReplacementRequest::STATUS_COMPLETED,
                BatteryReplacementRequest::STATUS_CANCELLED,
            ])->count() ?? 0,
            'savedAddressesCount' => $user->rider?->savedAddresses()->count() ?? 0,
            'recentOrdersCount' => $user->rider?->orders()->whereNotIn('status', [
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
            ])->count() ?? 0,
        ]);
    }
}
