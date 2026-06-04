<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProviderApplication;
use App\Models\Rider;
use App\Models\SellerProfile;
use App\Models\ServiceCenterProfile;
use App\Models\User;
use App\Support\AccessRoles;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);
});

test('new registrations are assigned the rider role', function () {
    $this->post(route('register'), [
        'name' => 'New Rider',
        'email' => 'new-rider@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertRedirect(route('rider.dashboard', absolute: false));

    $user = User::query()->where('email', 'new-rider@example.com')->firstOrFail();

    expect($user->hasRole(AccessRoles::RIDER))->toBeTrue()
        ->and($user->hasRole(AccessRoles::SELLER))->toBeFalse();
});

test('admin seed creates default admin users and redirects admin to admin dashboard', function () {
    $this->seed(AdminUserSeeder::class);

    $this->post(route('login'), [
        'email' => 'admin@example.com',
        'password' => 'password',
    ])
        ->assertRedirect(route('admin.dashboard', absolute: false));

    expect(User::where('email', 'superadmin@example.com')->firstOrFail()->hasRole(AccessRoles::SUPER_ADMIN))->toBeTrue()
        ->and(User::where('email', 'admin@example.com')->firstOrFail()->hasRole(AccessRoles::ADMIN))->toBeTrue();
});

test('rider can submit provider application and admin can approve it', function () {
    $riderUser = User::factory()->create();
    $riderUser->assignRole(AccessRoles::RIDER);
    Rider::factory()->for($riderUser)->create();

    $admin = User::factory()->create();
    $admin->assignRole(AccessRoles::ADMIN);

    $this->actingAs($riderUser)
        ->post(route('rider.provider-applications.store'), [
            'requested_role' => AccessRoles::SELLER,
            'business_name' => 'Moto Seller Store',
            'phone' => '+201011111111',
            'address' => '12 Market Street',
            'city' => 'Cairo',
            'description' => 'Selling rider accessories.',
        ])
        ->assertRedirect(route('rider.provider-applications.index'))
        ->assertSessionHasNoErrors();

    $application = ProviderApplication::query()->firstOrFail();

    $this->actingAs($admin)
        ->patch(route('admin.provider-applications.approve', $application), [
            'admin_notes' => 'Approved after review.',
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect($riderUser->fresh()->hasRole(AccessRoles::SELLER))->toBeTrue()
        ->and($application->fresh()->status)->toBe(AccessRoles::STATUS_APPROVED);

    $this->assertDatabaseHas('seller_profiles', [
        'user_id' => $riderUser->id,
        'store_name' => 'Moto Seller Store',
        'status' => AccessRoles::STATUS_APPROVED,
    ]);

    $this->actingAs($riderUser)
        ->post(route('rider.provider-applications.store'), [
            'requested_role' => AccessRoles::SELLER,
            'business_name' => 'Duplicate Moto Seller Store',
            'phone' => '+201022222222',
            'address' => '15 Market Street',
            'city' => 'Cairo',
        ])
        ->assertSessionHasErrors(['requested_role']);
});

test('suspended provider keeps role but cannot access provider dashboard', function () {
    $seller = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $seller->assignRole([AccessRoles::RIDER, AccessRoles::SELLER]);
    Rider::factory()->for($seller)->create();
    SellerProfile::factory()->for($seller)->create([
        'status' => AccessRoles::STATUS_SUSPENDED,
    ]);

    $this->actingAs($seller)
        ->get(route('seller.dashboard'))
        ->assertRedirect(route('rider.dashboard'))
        ->assertSessionHas('status', 'Your provider account is not active yet.');

    expect($seller->fresh()->hasRole(AccessRoles::SELLER))->toBeTrue();
});

test('approved seller can access seller dashboard', function () {
    $seller = User::factory()->create();
    $seller->assignRole([AccessRoles::RIDER, AccessRoles::SELLER]);
    Rider::factory()->for($seller)->create();
    SellerProfile::factory()->for($seller)->create([
        'status' => AccessRoles::STATUS_APPROVED,
    ]);

    $this->actingAs($seller)
        ->get(route('seller.dashboard'))
        ->assertOk()
        ->assertSee('Seller dashboard')
        ->assertSee('My products');
});

test('rider navbar shows customer links and counts', function () {
    $user = User::factory()->create();
    $user->assignRole(AccessRoles::RIDER);
    $rider = Rider::factory()->for($user)->create();

    CartItem::factory()->for($rider)->create(['quantity' => 2]);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee(__('rider.nav_dashboard'))
        ->assertSee(__('rider.nav_marketplace'))
        ->assertSee(__('rider.nav_services'))
        ->assertSee(__('rider.nav_garage'))
        ->assertSee(__('rider.nav_dealerships'))
        ->assertSee(__('rider.nav_cart'))
        ->assertSee('2')
        ->assertSee(__('rider.nav_apply_provider'));
});

test('approved providers see customer navigation plus approved business shortcuts', function () {
    $user = User::factory()->create();
    $user->assignRole([AccessRoles::RIDER, AccessRoles::SELLER, AccessRoles::SERVICE_CENTER]);
    Rider::factory()->for($user)->create();
    SellerProfile::factory()->for($user)->create(['status' => AccessRoles::STATUS_APPROVED]);
    ServiceCenterProfile::factory()->for($user)->create(['status' => AccessRoles::STATUS_APPROVED]);

    $this->actingAs($user)
        ->get(route('seller.dashboard'))
        ->assertOk()
        ->assertSee(__('rider.nav_marketplace'))
        ->assertSee(__('rider.nav_seller'))
        ->assertSee(__('rider.nav_service_center'))
        ->assertDontSee(__('rider.nav_provider_status'));
});

test('approved service center navbar dashboard targets service center dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole([AccessRoles::RIDER, AccessRoles::SERVICE_CENTER]);
    Rider::factory()->for($user)->create();
    ServiceCenterProfile::factory()->for($user)->create(['status' => AccessRoles::STATUS_APPROVED]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('service-center.dashboard'));

    $this->actingAs($user)
        ->get(route('rider.dashboard'))
        ->assertRedirect(route('service-center.dashboard'));

    $this->actingAs($user)
        ->get(route('service-center.dashboard'))
        ->assertOk()
        ->assertSee('Service center dashboard')
        ->assertSee(route('service-center.dashboard'), false)
        ->assertDontSee(route('rider.garage'), false)
        ->assertDontSee(route('rider.dashboard'), false);
});

test('approved service center can still open marketplace content', function () {
    $user = User::factory()->create();
    $user->assignRole([AccessRoles::RIDER, AccessRoles::SERVICE_CENTER]);
    Rider::factory()->for($user)->create();
    ServiceCenterProfile::factory()->for($user)->create(['status' => AccessRoles::STATUS_APPROVED]);

    $this->actingAs($user)
        ->get(route('rider.marketplace'))
        ->assertOk()
        ->assertSee('Marketplace and services')
        ->assertSee('Accessories')
        ->assertSee(route('service-center.dashboard'), false)
        ->assertDontSee('Service center dashboard')
        ->assertDontSee(route('rider.garage'), false)
        ->assertDontSee(route('rider.dashboard'), false);
});

test('pending and suspended providers keep customer nav without active business links', function () {
    $pendingSeller = User::factory()->create();
    $pendingSeller->assignRole([AccessRoles::RIDER, AccessRoles::SELLER]);
    Rider::factory()->for($pendingSeller)->create();
    SellerProfile::factory()->for($pendingSeller)->create(['status' => AccessRoles::STATUS_PENDING]);
    ProviderApplication::factory()->for($pendingSeller)->create([
        'requested_role' => AccessRoles::SELLER,
        'status' => AccessRoles::STATUS_PENDING,
    ]);

    $this->actingAs($pendingSeller)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee(__('rider.nav_marketplace'))
        ->assertSee(__('rider.nav_provider_status'))
        ->assertSee(__('rider.nav_pending_review'))
        ->assertDontSee(route('seller.dashboard', absolute: false));

    $suspendedSeller = User::factory()->create();
    $suspendedSeller->assignRole([AccessRoles::RIDER, AccessRoles::SELLER]);
    Rider::factory()->for($suspendedSeller)->create();
    SellerProfile::factory()->for($suspendedSeller)->create(['status' => AccessRoles::STATUS_SUSPENDED]);

    $this->actingAs($suspendedSeller)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee(__('rider.nav_marketplace'))
        ->assertSee(__('rider.nav_provider_status'))
        ->assertSee(__('rider.nav_suspended'))
        ->assertDontSee(route('seller.dashboard', absolute: false));
});

test('admin navbar is management focused', function () {
    $admin = User::factory()->create();
    $admin->assignRole(AccessRoles::ADMIN);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee(__('rider.nav_admin_dashboard'))
        ->assertSee(__('rider.nav_users'))
        ->assertSee(__('rider.nav_provider_applications'))
        ->assertSee(__('rider.nav_marketplace'))
        ->assertSee(__('rider.nav_sellers'))
        ->assertDontSee(__('rider.nav_cart'));
});

test('admin can monitor marketplace without rider actions', function () {
    $admin = User::factory()->create();
    $admin->assignRole(AccessRoles::ADMIN);

    Product::factory()->create([
        'name' => 'Admin Visible Helmet',
        'type' => Product::TYPE_ACCESSORY,
        'status' => Product::STATUS_ACTIVE,
    ]);

    $this->actingAs($admin)
        ->get(route('rider.marketplace'))
        ->assertOk()
        ->assertSee('Marketplace and services')
        ->assertSee('Admin monitoring')
        ->assertSee(route('admin.dashboard'), false)
        ->assertDontSee(route('rider.garage'), false)
        ->assertDontSee(route('rider.cart.index'), false)
        ->assertDontSee('My Orders');

    $this->actingAs($admin)
        ->get(route('rider.products.accessories'))
        ->assertOk()
        ->assertSee('Admin Visible Helmet')
        ->assertSee('Customer actions hidden')
        ->assertDontSee('Add to Cart')
        ->assertDontSee('Save to Wishlist');
});
