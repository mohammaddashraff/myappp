<?php

use App\Models\DealerInquiry;
use App\Models\DealerMotorcycle;
use App\Models\DealershipProfile;
use App\Models\DeliveryPartnerProfile;
use App\Models\DeliveryTask;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rider;
use App\Models\RoadsideProviderProfile;
use App\Models\RoadsideRequest;
use App\Models\SellerProfile;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\ServiceCenterProfile;
use App\Models\User;
use App\Support\AccessRoles;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);
});

test('seller can create own product and cannot edit another seller product', function () {
    Storage::fake('public');

    $seller = User::factory()->create();
    $seller->assignRole([AccessRoles::RIDER, AccessRoles::SELLER]);
    Rider::factory()->for($seller)->create();
    $profile = SellerProfile::factory()->for($seller)->create(['status' => AccessRoles::STATUS_APPROVED]);

    $otherSeller = User::factory()->create();
    $otherProfile = SellerProfile::factory()->for($otherSeller)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $otherProduct = Product::factory()->for($otherProfile)->create();

    $this->actingAs($seller)
        ->post(route('seller.products.store'), [
            'type' => Product::TYPE_ACCESSORY,
            'name' => 'Touring Helmet',
            'description' => 'Safe full face helmet.',
            'category' => 'Helmets',
            'brand' => 'LS2',
            'price' => 3200,
            'stock_quantity' => 8,
            'condition' => 'new',
            'status' => 'active',
            'image_upload' => UploadedFile::fake()->image('helmet.jpg'),
        ])
        ->assertRedirect(route('seller.products.index'));

    $createdProduct = Product::query()->where('name', 'Touring Helmet')->firstOrFail();

    expect($createdProduct->seller_profile_id)->toBe($profile->id)
        ->and($createdProduct->image)->toStartWith('products/');

    Storage::disk('public')->assertExists($createdProduct->image);

    $this->assertDatabaseHas('products', [
        'seller_profile_id' => $profile->id,
        'name' => 'Touring Helmet',
    ]);

    $this->actingAs($seller)
        ->patch(route('seller.products.update', $otherProduct), [
            'type' => Product::TYPE_ACCESSORY,
            'name' => 'Blocked',
            'description' => 'Blocked',
            'category' => 'Helmets',
            'brand' => 'LS2',
            'price' => 100,
            'stock_quantity' => 1,
            'condition' => 'new',
            'status' => 'active',
        ])
        ->assertForbidden();
});

test('service center only manages bookings for own services', function () {
    $centerUser = User::factory()->create();
    $centerUser->assignRole([AccessRoles::RIDER, AccessRoles::SERVICE_CENTER]);
    $profile = ServiceCenterProfile::factory()->for($centerUser)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $service = Service::factory()->for($profile)->create();
    $booking = ServiceBooking::factory()->for($service)->create();

    $otherProfile = ServiceCenterProfile::factory()->create(['status' => AccessRoles::STATUS_APPROVED]);
    $otherBooking = ServiceBooking::factory()->for(Service::factory()->for($otherProfile))->create();

    $this->actingAs($centerUser)
        ->patch(route('service-center.bookings.update', $booking), ['status' => 'accepted'])
        ->assertRedirect();

    $this->actingAs($centerUser)
        ->patch(route('service-center.bookings.update', $otherBooking), ['status' => 'accepted'])
        ->assertForbidden();
});

test('roadside provider can accept available request but not another assigned request', function () {
    $provider = User::factory()->create();
    $provider->assignRole([AccessRoles::RIDER, AccessRoles::ROADSIDE_PROVIDER]);
    $profile = RoadsideProviderProfile::factory()->for($provider)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $request = RoadsideRequest::factory()->create();

    $otherProfile = RoadsideProviderProfile::factory()->create(['status' => AccessRoles::STATUS_APPROVED]);
    $assigned = RoadsideRequest::factory()->create([
        'roadside_provider_profile_id' => $otherProfile->id,
        'status' => 'accepted',
    ]);

    $this->actingAs($provider)
        ->patch(route('roadside-provider.requests.accept', $request))
        ->assertRedirect();

    expect($request->fresh()->roadside_provider_profile_id)->toBe($profile->id);

    $this->actingAs($provider)
        ->patch(route('roadside-provider.requests.update', $assigned), ['status' => 'completed'])
        ->assertForbidden();
});

test('delivery partner can accept available tasks only', function () {
    $partner = User::factory()->create();
    $partner->assignRole([AccessRoles::RIDER, AccessRoles::DELIVERY_PARTNER]);
    $profile = DeliveryPartnerProfile::factory()->for($partner)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $task = DeliveryTask::query()->create([
        'pickup_address' => 'Store',
        'dropoff_address' => 'Rider',
        'phone' => '+201011111111',
        'status' => 'pending',
    ]);

    $this->actingAs($partner)
        ->patch(route('delivery-partner.tasks.accept', $task))
        ->assertRedirect();

    expect($task->fresh()->delivery_partner_profile_id)->toBe($profile->id);
});

test('dealership can manage own listing inquiry status only', function () {
    $dealer = User::factory()->create();
    $dealer->assignRole([AccessRoles::RIDER, AccessRoles::DEALERSHIP]);
    $profile = DealershipProfile::factory()->for($dealer)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $listing = DealerMotorcycle::factory()->for($profile)->create();
    $inquiry = DealerInquiry::factory()->for($listing, 'motorcycle')->create();

    $otherProfile = DealershipProfile::factory()->create(['status' => AccessRoles::STATUS_APPROVED]);
    $otherInquiry = DealerInquiry::factory()->for(DealerMotorcycle::factory()->for($otherProfile), 'motorcycle')->create();

    $this->actingAs($dealer)
        ->patch(route('dealership.inquiries.update', $inquiry), ['status' => 'contacted'])
        ->assertRedirect();

    $this->actingAs($dealer)
        ->patch(route('dealership.inquiries.update', $otherInquiry), ['status' => 'contacted'])
        ->assertForbidden();
});

test('provider status actions cannot move completed work backwards', function () {
    $seller = User::factory()->create();
    $seller->assignRole([AccessRoles::RIDER, AccessRoles::SELLER]);
    $sellerProfile = SellerProfile::factory()->for($seller)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $product = Product::factory()->for($sellerProfile)->create();
    $order = Order::factory()->create(['status' => Order::STATUS_DELIVERED]);
    OrderItem::factory()->for($order)->for($product)->create();

    $this->actingAs($seller)
        ->patch(route('seller.orders.update', $order), ['status' => Order::STATUS_PENDING])
        ->assertSessionHasErrors(['status']);

    expect($order->fresh()->status)->toBe(Order::STATUS_DELIVERED);

    $centerUser = User::factory()->create();
    $centerUser->assignRole([AccessRoles::RIDER, AccessRoles::SERVICE_CENTER]);
    $centerProfile = ServiceCenterProfile::factory()->for($centerUser)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $booking = ServiceBooking::factory()
        ->for(Service::factory()->for($centerProfile))
        ->create(['status' => ServiceBooking::STATUS_COMPLETED]);

    $this->actingAs($centerUser)
        ->patch(route('service-center.bookings.update', $booking), ['status' => ServiceBooking::STATUS_REJECTED])
        ->assertSessionHasErrors(['status']);

    expect($booking->fresh()->status)->toBe(ServiceBooking::STATUS_COMPLETED);

    $roadsideUser = User::factory()->create();
    $roadsideUser->assignRole([AccessRoles::RIDER, AccessRoles::ROADSIDE_PROVIDER]);
    $roadsideProfile = RoadsideProviderProfile::factory()->for($roadsideUser)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $roadsideRequest = RoadsideRequest::factory()->create([
        'roadside_provider_profile_id' => $roadsideProfile->id,
        'status' => RoadsideRequest::STATUS_COMPLETED,
    ]);

    $this->actingAs($roadsideUser)
        ->patch(route('roadside-provider.requests.update', $roadsideRequest), ['status' => RoadsideRequest::STATUS_ON_THE_WAY])
        ->assertSessionHasErrors(['status']);

    expect($roadsideRequest->fresh()->status)->toBe(RoadsideRequest::STATUS_COMPLETED);

    $partner = User::factory()->create();
    $partner->assignRole([AccessRoles::RIDER, AccessRoles::DELIVERY_PARTNER]);
    $deliveryProfile = DeliveryPartnerProfile::factory()->for($partner)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $deliveryTask = DeliveryTask::query()->create([
        'delivery_partner_profile_id' => $deliveryProfile->id,
        'pickup_address' => 'Store',
        'dropoff_address' => 'Rider',
        'phone' => '+201011111111',
        'status' => DeliveryTask::STATUS_DELIVERED,
    ]);

    $this->actingAs($partner)
        ->patch(route('delivery-partner.tasks.update', $deliveryTask), ['status' => DeliveryTask::STATUS_ASSIGNED])
        ->assertSessionHasErrors(['status']);

    expect($deliveryTask->fresh()->status)->toBe(DeliveryTask::STATUS_DELIVERED);

    $dealer = User::factory()->create();
    $dealer->assignRole([AccessRoles::RIDER, AccessRoles::DEALERSHIP]);
    $dealershipProfile = DealershipProfile::factory()->for($dealer)->create(['status' => AccessRoles::STATUS_APPROVED]);
    $inquiry = DealerInquiry::factory()
        ->for(DealerMotorcycle::factory()->for($dealershipProfile), 'motorcycle')
        ->create(['status' => DealerInquiry::STATUS_CLOSED]);

    $this->actingAs($dealer)
        ->patch(route('dealership.inquiries.update', $inquiry), ['status' => DealerInquiry::STATUS_PENDING])
        ->assertSessionHasErrors(['status']);

    expect($inquiry->fresh()->status)->toBe(DealerInquiry::STATUS_CLOSED);
});
