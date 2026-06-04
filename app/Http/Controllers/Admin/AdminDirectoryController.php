<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DealershipProfile;
use App\Models\DeliveryPartnerProfile;
use App\Models\RoadsideProviderProfile;
use App\Models\SellerProfile;
use App\Models\ServiceCenterProfile;
use App\Models\User;
use Illuminate\View\View;

class AdminDirectoryController extends Controller
{
    public function users(): View
    {
        return view('admin.directories.users', [
            'users' => User::query()->with('roles')->latest()->paginate(20),
        ]);
    }

    public function sellers(): View
    {
        return view('admin.directories.providers', [
            'title' => 'Sellers',
            'nameField' => 'store_name',
            'profiles' => SellerProfile::query()->with('user')->latest()->paginate(20),
        ]);
    }

    public function serviceCenters(): View
    {
        return view('admin.directories.providers', [
            'title' => 'Service centers',
            'nameField' => 'center_name',
            'profiles' => ServiceCenterProfile::query()->with('user')->latest()->paginate(20),
        ]);
    }

    public function roadsideProviders(): View
    {
        return view('admin.directories.providers', [
            'title' => 'Roadside providers',
            'nameField' => 'provider_name',
            'profiles' => RoadsideProviderProfile::query()->with('user')->latest()->paginate(20),
        ]);
    }

    public function deliveryPartners(): View
    {
        return view('admin.directories.providers', [
            'title' => 'Delivery partners',
            'nameField' => 'full_name',
            'profiles' => DeliveryPartnerProfile::query()->with('user')->latest()->paginate(20),
        ]);
    }

    public function dealerships(): View
    {
        return view('admin.directories.providers', [
            'title' => 'Dealerships',
            'nameField' => 'dealership_name',
            'profiles' => DealershipProfile::query()->with('user')->latest()->paginate(20),
        ]);
    }
}
