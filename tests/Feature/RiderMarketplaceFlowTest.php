<?php

use App\Models\BatteryReplacementRequest;
use App\Models\CartItem;
use App\Models\Dealer;
use App\Models\DealerInquiry;
use App\Models\DealerMotorcycle;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rider;
use App\Models\RiderSavedAddress;
use App\Models\RoadsideRequest;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\User;

test('rider can open the marketplace home', function () {
    $user = User::factory()->create();
    Rider::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('rider.marketplace'))
        ->assertOk()
        ->assertSee('Accessories')
        ->assertSee('Spare Parts')
        ->assertSee('Roadside Assistance')
        ->assertSee('My Requests');
});

test('rider can add products to cart and create an order', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create([
        'current_address' => '12 Nile Street, Cairo',
    ]);
    $product = Product::factory()->create([
        'name' => 'Apex Test Helmet',
        'price' => 1000,
        'stock_quantity' => 3,
    ]);

    $this->actingAs($user)
        ->get(route('rider.products.accessories', ['q' => 'Apex']))
        ->assertOk()
        ->assertSee('Apex Test Helmet');

    $this->actingAs($user)
        ->post(route('rider.cart.store', $product), ['quantity' => 2])
        ->assertRedirect(route('rider.cart.index'));

    $this->assertDatabaseHas('cart_items', [
        'rider_id' => $rider->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->post(route('rider.checkout.store'), [
            'delivery_method' => 'delivery',
            'payment_method' => 'cash_on_delivery',
            'address_choice' => 'saved',
        ])
        ->assertRedirect();

    $order = Order::query()->with('items')->firstOrFail();

    expect($order->rider_id)->toBe($rider->id)
        ->and((float) $order->subtotal)->toBe(2000.0)
        ->and((float) $order->delivery_fee)->toBe(75.0)
        ->and($order->status)->toBe('pending')
        ->and($order->items)->toHaveCount(1);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_name' => 'Apex Test Helmet',
        'product_price' => 1000,
        'quantity' => 2,
        'total_price' => 2000,
    ]);
    $this->assertDatabaseMissing('cart_items', [
        'rider_id' => $rider->id,
        'product_id' => $product->id,
    ]);
    expect($product->fresh()->stock_quantity)->toBe(1);

    $this->actingAs($user)
        ->get(route('rider.orders.show', $order))
        ->assertOk()
        ->assertSee($order->order_number)
        ->assertSee('Pending');
});

test('cart quantity cannot exceed available stock', function () {
    $user = User::factory()->create();
    Rider::factory()->for($user)->create();
    $product = Product::factory()->create([
        'stock_quantity' => 1,
    ]);

    $this->actingAs($user)
        ->post(route('rider.cart.store', $product), ['quantity' => 2])
        ->assertSessionHasErrors(['quantity']);

    expect(CartItem::query()->count())->toBe(0);
});

test('product details page shows compact related products', function () {
    $user = User::factory()->create();
    Rider::factory()->for($user)->create();
    $product = Product::factory()->create([
        'name' => 'Apex Full Face Helmet',
        'type' => Product::TYPE_ACCESSORY,
    ]);
    $relatedProduct = Product::factory()->create([
        'name' => 'Urban Grip Riding Gloves',
        'type' => Product::TYPE_ACCESSORY,
    ]);

    $this->actingAs($user)
        ->get(route('rider.products.show', $product))
        ->assertOk()
        ->assertSee('Apex Full Face Helmet')
        ->assertSee('More like this')
        ->assertSee('Urban Grip Riding Gloves')
        ->assertSee(route('rider.products.show', $relatedProduct), false);
});

test('cart quantity controls render without a manual update button', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create();
    $product = Product::factory()->create([
        'name' => 'Auto Quantity Helmet',
        'stock_quantity' => 5,
    ]);
    CartItem::factory()->for($rider)->for($product)->create([
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->get(route('rider.cart.index'))
        ->assertOk()
        ->assertSee('Auto Quantity Helmet')
        ->assertSee('Increase quantity')
        ->assertSee('Decrease quantity')
        ->assertDontSee('Update');
});

test('checkout can store a structured delivery address', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create([
        'current_address' => null,
    ]);
    $product = Product::factory()->create([
        'price' => 900,
        'stock_quantity' => 4,
    ]);
    CartItem::factory()->for($rider)->for($product)->create([
        'quantity' => 1,
    ]);

    $this->actingAs($user)
        ->post(route('rider.checkout.store'), [
            'delivery_method' => 'delivery',
            'payment_method' => 'cash_on_delivery',
            'address_choice' => 'new',
            'address_city' => 'Cairo',
            'address_area' => 'Nasr City',
            'address_street' => 'Abbas El Akkad Street',
            'address_building' => '12B',
            'address_floor' => '5',
            'address_apartment' => '502',
            'address_landmark' => 'Near the fuel station',
            'address_notes' => 'Call before arriving',
        ])
        ->assertRedirect();

    $order = Order::query()->firstOrFail();

    expect($order->rider_id)->toBe($rider->id)
        ->and($order->address)->toContain('Apartment 502')
        ->and($order->address)->toContain('Floor 5')
        ->and($order->address)->toContain('Building 12B')
        ->and($order->address)->toContain('Abbas El Akkad Street')
        ->and($order->address)->toContain('Landmark: Near the fuel station')
        ->and($order->address)->toContain('Notes: Call before arriving');
});

test('checkout can use a saved rider address', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create([
        'current_address' => null,
    ]);
    $product = Product::factory()->create([
        'price' => 500,
        'stock_quantity' => 3,
    ]);
    $address = RiderSavedAddress::factory()->for($rider)->default()->create([
        'label' => 'Home',
        'city' => 'Cairo',
        'area' => 'Heliopolis',
        'street' => 'Saved Street',
        'building' => '40',
        'floor' => '6',
        'apartment' => '61',
    ]);
    CartItem::factory()->for($rider)->for($product)->create([
        'quantity' => 1,
    ]);

    $this->actingAs($user)
        ->post(route('rider.checkout.store'), [
            'delivery_method' => 'delivery',
            'payment_method' => 'cash_on_delivery',
            'address_choice' => 'saved',
            'saved_address_id' => $address->id,
        ])
        ->assertRedirect();

    $order = Order::query()->firstOrFail();

    expect($order->address)->toContain('Apartment 61')
        ->and($order->address)->toContain('Saved Street')
        ->and((float) $order->delivery_fee)->toBe(75.0);
});

test('rider can book a service and view booking details', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create([
        'phone_number' => '+201001234567',
    ]);
    $service = Service::factory()->create([
        'name' => 'Premium Oil Change Test',
        'estimated_price' => 380,
    ]);

    $this->actingAs($user)
        ->post(route('rider.bookings.store', $service), [
            'booking_date' => now()->addDay()->toDateString(),
            'preferred_time' => '11:30',
            'location_option' => 'visit_workshop',
            'contact_phone' => '+201001234567',
            'notes' => 'Please check the filter.',
        ])
        ->assertRedirect();

    $booking = ServiceBooking::query()->firstOrFail();

    expect($booking->rider_id)->toBe($rider->id)
        ->and($booking->status)->toBe('pending')
        ->and((float) $booking->estimated_price)->toBe(380.0);

    $this->actingAs($user)
        ->get(route('rider.bookings.show', $booking))
        ->assertOk()
        ->assertSee($booking->booking_number)
        ->assertSee('Premium Oil Change Test');
});

test('rider can create and track roadside battery and dealer requests', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create([
        'full_name' => 'Rider Example',
        'phone_number' => '+201009999999',
    ]);
    $battery = Product::factory()->battery()->create([
        'name' => 'VoltX Test Battery',
    ]);
    $dealer = Dealer::factory()->create([
        'name' => 'Cairo Test Showroom',
    ]);
    $dealerMotorcycle = DealerMotorcycle::factory()->for($dealer)->create([
        'brand' => 'Honda',
        'model' => 'CBR150R',
    ]);

    $this->actingAs($user)
        ->post(route('rider.roadside.store'), [
            'assistance_type' => 'Towing',
            'location' => 'Ring Road exit 12',
            'description' => 'Engine stopped suddenly.',
            'contact_phone' => '+201009999999',
        ])
        ->assertRedirect();

    $this->actingAs($user)
        ->post(route('rider.batteries.installation.store', $battery), [
            'location' => 'Maadi, Cairo',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time' => '13:00',
            'contact_phone' => '+201009999999',
            'notes' => 'Call before arriving.',
        ])
        ->assertRedirect();

    $this->actingAs($user)
        ->post(route('rider.dealer-motorcycles.inquiries.store', [$dealer, $dealerMotorcycle]), [
            'rider_name' => 'Rider Example',
            'phone' => '+201009999999',
            'message' => 'Is this motorcycle available for viewing?',
            'preferred_contact_method' => 'phone',
        ])
        ->assertRedirect();

    expect(RoadsideRequest::query()->whereBelongsTo($rider)->count())->toBe(1)
        ->and(BatteryReplacementRequest::query()->whereBelongsTo($rider)->count())->toBe(1)
        ->and(DealerInquiry::query()->whereBelongsTo($rider)->count())->toBe(1);

    $this->actingAs($user)
        ->get(route('rider.requests.index'))
        ->assertOk()
        ->assertSee('Roadside Assistance')
        ->assertSee('Battery Replacement')
        ->assertSee('Dealer Inquiry');
});
