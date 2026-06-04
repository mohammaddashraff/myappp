<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDirectoryController;
use App\Http\Controllers\Admin\ProviderApplicationReviewController;
use App\Http\Controllers\Dealership\DealershipInquiryController;
use App\Http\Controllers\Dealership\DealershipListingController;
use App\Http\Controllers\Dealership\DealershipProfileController;
use App\Http\Controllers\DeliveryPartner\DeliveryPartnerProfileController;
use App\Http\Controllers\DeliveryPartner\DeliveryTaskController;
use App\Http\Controllers\DriverApplicationStatusController;
use App\Http\Controllers\DriverDashboardController;
use App\Http\Controllers\DriverSignupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderApplicationController;
use App\Http\Controllers\RiderBatteryController;
use App\Http\Controllers\RiderCartController;
use App\Http\Controllers\RiderCheckoutController;
use App\Http\Controllers\RiderDashboardController;
use App\Http\Controllers\RiderDealerController;
use App\Http\Controllers\RiderMarketplaceController;
use App\Http\Controllers\RiderMotorcycleController;
use App\Http\Controllers\RiderOrderController;
use App\Http\Controllers\RiderProductController;
use App\Http\Controllers\RiderProfileController;
use App\Http\Controllers\RiderRequestController;
use App\Http\Controllers\RiderRoadsideRequestController;
use App\Http\Controllers\RiderSavedAddressController;
use App\Http\Controllers\RiderServiceBookingController;
use App\Http\Controllers\RiderServiceController;
use App\Http\Controllers\RiderWishlistController;
use App\Http\Controllers\RoadsideProvider\RoadsideProviderProfileController;
use App\Http\Controllers\RoadsideProvider\RoadsideProviderRequestController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\ServiceCenter\ServiceCenterBookingController;
use App\Http\Controllers\ServiceCenter\ServiceCenterProfileController;
use App\Http\Controllers\ServiceCenter\ServiceCenterServiceController;
use App\Support\RoleRedirector;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);

    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

Route::get('/drivers/signup', [DriverSignupController::class, 'create'])
    ->name('drivers.signup.create');

Route::get('/drivers/signup/success', [DriverSignupController::class, 'success'])
    ->name('drivers.signup.success');

Route::get('/drivers/signup/{step}', [DriverSignupController::class, 'show'])
    ->whereIn('step', ['account', 'identity', 'contact', 'documents', 'vehicle', 'review'])
    ->name('drivers.signup.step');

Route::post('/drivers/signup/{step}', [DriverSignupController::class, 'store'])
    ->whereIn('step', ['account', 'identity', 'contact', 'documents', 'vehicle', 'review'])
    ->name('drivers.signup.step.store');

Route::get('/dashboard', function () {
    return redirect()->route(RoleRedirector::routeNameFor(auth()->user()));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:super_admin|admin')
        ->group(function (): void {
            Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
            Route::get('/users', [AdminDirectoryController::class, 'users'])->name('users.index');
            Route::get('/sellers', [AdminDirectoryController::class, 'sellers'])->name('sellers.index');
            Route::get('/service-centers', [AdminDirectoryController::class, 'serviceCenters'])->name('service-centers.index');
            Route::get('/roadside-providers', [AdminDirectoryController::class, 'roadsideProviders'])->name('roadside-providers.index');
            Route::get('/delivery-partners', [AdminDirectoryController::class, 'deliveryPartners'])->name('delivery-partners.index');
            Route::get('/dealerships', [AdminDirectoryController::class, 'dealerships'])->name('dealerships.index');
            Route::get('/provider-applications', [ProviderApplicationReviewController::class, 'index'])->name('provider-applications.index');
            Route::get('/provider-applications/{providerApplication}', [ProviderApplicationReviewController::class, 'show'])->name('provider-applications.show');
            Route::patch('/provider-applications/{providerApplication}/approve', [ProviderApplicationReviewController::class, 'approve'])->name('provider-applications.approve');
            Route::patch('/provider-applications/{providerApplication}/reject', [ProviderApplicationReviewController::class, 'reject'])->name('provider-applications.reject');
            Route::patch('/provider-applications/{providerApplication}/suspend', [ProviderApplicationReviewController::class, 'suspend'])->name('provider-applications.suspend');
            Route::patch('/provider-applications/{providerApplication}/activate', [ProviderApplicationReviewController::class, 'activate'])->name('provider-applications.activate');
        });

    Route::prefix('seller')
        ->name('seller.')
        ->middleware('provider.approved:seller')
        ->group(function (): void {
            Route::get('/dashboard', [RoleDashboardController::class, 'seller'])->name('dashboard');
            Route::resource('products', SellerProductController::class)->except('show');
            Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
            Route::patch('/orders/{order}', [SellerOrderController::class, 'update'])->name('orders.update');
            Route::get('/profile', [SellerProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [SellerProfileController::class, 'update'])->name('profile.update');
        });

    Route::prefix('service-center')
        ->name('service-center.')
        ->middleware('provider.approved:service_center')
        ->group(function (): void {
            Route::get('/dashboard', [RoleDashboardController::class, 'serviceCenter'])->name('dashboard');
            Route::resource('services', ServiceCenterServiceController::class)->except('show');
            Route::get('/bookings', [ServiceCenterBookingController::class, 'index'])->name('bookings.index');
            Route::patch('/bookings/{booking}', [ServiceCenterBookingController::class, 'update'])->name('bookings.update');
            Route::get('/profile', [ServiceCenterProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ServiceCenterProfileController::class, 'update'])->name('profile.update');
        });

    Route::prefix('roadside-provider')
        ->name('roadside-provider.')
        ->middleware('provider.approved:roadside_provider')
        ->group(function (): void {
            Route::get('/dashboard', [RoleDashboardController::class, 'roadsideProvider'])->name('dashboard');
            Route::get('/requests', [RoadsideProviderRequestController::class, 'index'])->name('requests.index');
            Route::patch('/requests/{roadsideRequest}/accept', [RoadsideProviderRequestController::class, 'accept'])->name('requests.accept');
            Route::patch('/requests/{roadsideRequest}', [RoadsideProviderRequestController::class, 'update'])->name('requests.update');
            Route::get('/profile', [RoadsideProviderProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [RoadsideProviderProfileController::class, 'update'])->name('profile.update');
        });

    Route::prefix('delivery-partner')
        ->name('delivery-partner.')
        ->middleware('provider.approved:delivery_partner')
        ->group(function (): void {
            Route::get('/dashboard', [RoleDashboardController::class, 'deliveryPartner'])->name('dashboard');
            Route::get('/tasks', [DeliveryTaskController::class, 'index'])->name('tasks.index');
            Route::patch('/tasks/{deliveryTask}/accept', [DeliveryTaskController::class, 'accept'])->name('tasks.accept');
            Route::patch('/tasks/{deliveryTask}', [DeliveryTaskController::class, 'update'])->name('tasks.update');
            Route::get('/profile', [DeliveryPartnerProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [DeliveryPartnerProfileController::class, 'update'])->name('profile.update');
        });

    Route::prefix('dealership')
        ->name('dealership.')
        ->middleware('provider.approved:dealership')
        ->group(function (): void {
            Route::get('/dashboard', [RoleDashboardController::class, 'dealership'])->name('dashboard');
            Route::resource('listings', DealershipListingController::class)->parameters(['listings' => 'listing'])->except('show');
            Route::get('/inquiries', [DealershipInquiryController::class, 'index'])->name('inquiries.index');
            Route::patch('/inquiries/{inquiry}', [DealershipInquiryController::class, 'update'])->name('inquiries.update');
            Route::get('/profile', [DealershipProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [DealershipProfileController::class, 'update'])->name('profile.update');
        });

    Route::get('/rider/dashboard', RiderDashboardController::class)
        ->name('rider.dashboard');
    Route::get('/rider/provider-applications', [ProviderApplicationController::class, 'index'])
        ->name('rider.provider-applications.index');
    Route::get('/rider/provider-applications/create', [ProviderApplicationController::class, 'create'])
        ->name('rider.provider-applications.create');
    Route::post('/rider/provider-applications', [ProviderApplicationController::class, 'store'])
        ->name('rider.provider-applications.store');

    Route::get('/rider/marketplace', RiderMarketplaceController::class)
        ->name('rider.marketplace');
    Route::get('/rider/marketplace/accessories', [RiderProductController::class, 'accessories'])
        ->name('rider.products.accessories');
    Route::get('/rider/marketplace/spare-parts', [RiderProductController::class, 'spareParts'])
        ->name('rider.products.spare-parts');
    Route::get('/rider/marketplace/products/{product}', [RiderProductController::class, 'show'])
        ->name('rider.products.show');
    Route::post('/rider/wishlist/{product}', [RiderWishlistController::class, 'store'])
        ->name('rider.wishlist.store');
    Route::delete('/rider/wishlist/{wishlistItem}', [RiderWishlistController::class, 'destroy'])
        ->name('rider.wishlist.destroy');
    Route::post('/rider/marketplace/products/{product}/cart', [RiderCartController::class, 'store'])
        ->name('rider.cart.store');
    Route::get('/rider/cart', [RiderCartController::class, 'index'])
        ->name('rider.cart.index');
    Route::patch('/rider/cart/{cartItem}', [RiderCartController::class, 'update'])
        ->name('rider.cart.update');
    Route::delete('/rider/cart/{cartItem}', [RiderCartController::class, 'destroy'])
        ->name('rider.cart.destroy');
    Route::get('/rider/checkout', [RiderCheckoutController::class, 'show'])
        ->name('rider.checkout.show');
    Route::post('/rider/checkout', [RiderCheckoutController::class, 'store'])
        ->name('rider.checkout.store');
    Route::get('/rider/my-orders', [RiderOrderController::class, 'index'])
        ->name('rider.orders.index');
    Route::get('/rider/my-orders/{order}', [RiderOrderController::class, 'show'])
        ->name('rider.orders.show');

    Route::get('/rider/services', [RiderServiceController::class, 'index'])
        ->name('rider.services.index');
    Route::get('/rider/services/{service}', [RiderServiceController::class, 'show'])
        ->name('rider.services.show');
    Route::get('/rider/services/{service}/book', [RiderServiceBookingController::class, 'create'])
        ->name('rider.bookings.create');
    Route::post('/rider/services/{service}/book', [RiderServiceBookingController::class, 'store'])
        ->name('rider.bookings.store');
    Route::get('/rider/my-bookings', [RiderServiceBookingController::class, 'index'])
        ->name('rider.bookings.index');
    Route::get('/rider/my-bookings/{booking}', [RiderServiceBookingController::class, 'show'])
        ->name('rider.bookings.show');

    Route::get('/rider/roadside-assistance', [RiderRoadsideRequestController::class, 'create'])
        ->name('rider.roadside.create');
    Route::post('/rider/roadside-assistance', [RiderRoadsideRequestController::class, 'store'])
        ->name('rider.roadside.store');
    Route::get('/rider/batteries', [RiderBatteryController::class, 'index'])
        ->name('rider.batteries.index');
    Route::get('/rider/batteries/{product}/installation', [RiderBatteryController::class, 'createInstallation'])
        ->name('rider.batteries.installation.create');
    Route::post('/rider/batteries/{product}/installation', [RiderBatteryController::class, 'storeInstallation'])
        ->name('rider.batteries.installation.store');

    Route::get('/rider/dealers', [RiderDealerController::class, 'index'])
        ->name('rider.dealers.index');
    Route::get('/rider/dealers/{dealer}', [RiderDealerController::class, 'show'])
        ->name('rider.dealers.show');
    Route::get('/rider/dealer-motorcycles/{dealerMotorcycle}', [RiderDealerController::class, 'motorcycle'])
        ->name('rider.dealer-motorcycles.show');
    Route::get('/rider/dealers/{dealer}/inquiries/create', [RiderDealerController::class, 'createInquiry'])
        ->name('rider.dealers.inquiries.create');
    Route::post('/rider/dealers/{dealer}/inquiries', [RiderDealerController::class, 'storeInquiry'])
        ->name('rider.dealers.inquiries.store');
    Route::get('/rider/dealers/{dealer}/motorcycles/{dealerMotorcycle}/inquiries/create', [RiderDealerController::class, 'createInquiry'])
        ->name('rider.dealer-motorcycles.inquiries.create');
    Route::post('/rider/dealers/{dealer}/motorcycles/{dealerMotorcycle}/inquiries', [RiderDealerController::class, 'storeInquiry'])
        ->name('rider.dealer-motorcycles.inquiries.store');
    Route::get('/rider/my-requests', [RiderRequestController::class, 'index'])
        ->name('rider.requests.index');
    Route::get('/rider/my-requests/{type}/{id}', [RiderRequestController::class, 'show'])
        ->name('rider.requests.show');

    Route::get('/rider/garage', [RiderMotorcycleController::class, 'index'])
        ->name('rider.garage');
    Route::get('/rider/motorcycle-brands/{motorcycleBrand}/models', [RiderMotorcycleController::class, 'models'])
        ->name('rider.motorcycle-brands.models');
    Route::get('/rider/motorcycles/create', [RiderMotorcycleController::class, 'create'])
        ->name('rider.motorcycles.create');
    Route::post('/rider/motorcycles', [RiderMotorcycleController::class, 'store'])
        ->name('rider.motorcycles.store');
    Route::get('/rider/motorcycles/{motorcycle}', [RiderMotorcycleController::class, 'show'])
        ->name('rider.motorcycles.show');
    Route::get('/rider/motorcycles/{motorcycle}/edit', [RiderMotorcycleController::class, 'edit'])
        ->name('rider.motorcycles.edit');
    Route::match(['put', 'patch'], '/rider/motorcycles/{motorcycle}', [RiderMotorcycleController::class, 'update'])
        ->name('rider.motorcycles.update');
    Route::delete('/rider/motorcycles/{motorcycle}', [RiderMotorcycleController::class, 'destroy'])
        ->name('rider.motorcycles.destroy');

    Route::get('/rider/profile/edit', [RiderProfileController::class, 'edit'])
        ->name('rider.profile.edit');

    Route::patch('/rider/profile', [RiderProfileController::class, 'update'])
        ->name('rider.profile.update');
    Route::patch('/rider/profile/password', [RiderProfileController::class, 'updatePassword'])
        ->name('rider.profile.password.update');
    Route::post('/rider/profile/addresses', [RiderSavedAddressController::class, 'store'])
        ->name('rider.profile.addresses.store');
    Route::patch('/rider/profile/addresses/{riderSavedAddress}', [RiderSavedAddressController::class, 'update'])
        ->name('rider.profile.addresses.update');
    Route::delete('/rider/profile/addresses/{riderSavedAddress}', [RiderSavedAddressController::class, 'destroy'])
        ->name('rider.profile.addresses.destroy');

    Route::get('/drivers/dashboard', DriverDashboardController::class)
        ->name('drivers.dashboard');

    Route::get('/drivers/application/status', DriverApplicationStatusController::class)
        ->name('drivers.application.status');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
