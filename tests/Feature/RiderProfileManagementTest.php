<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rider;
use App\Models\RiderSavedAddress;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Hash;

test('rider profile shows saved addresses wishlist and history', function () {
    $user = User::factory()->create([
        'email' => 'rider@example.com',
    ]);
    $rider = Rider::factory()->for($user)->create([
        'full_name' => 'Profile Rider',
    ]);
    RiderSavedAddress::factory()->for($rider)->default()->create([
        'label' => 'Home',
        'city' => 'Cairo',
        'area' => 'Nasr City',
        'street' => 'Main street',
        'building' => '12B',
        'floor' => '5',
        'apartment' => '502',
    ]);
    $product = Product::factory()->create([
        'name' => 'Wishlist Test Gloves',
        'price' => 850,
    ]);
    WishlistItem::factory()->for($rider)->for($product)->create();
    $order = Order::factory()->for($rider)->create([
        'order_number' => 'ORD-PROFILE-001',
    ]);
    OrderItem::factory()->for($order)->for($product)->create([
        'product_name' => $product->name,
    ]);

    $this->actingAs($user)
        ->get(route('rider.profile.edit'))
        ->assertOk()
        ->assertSee('Profile Rider')
        ->assertSee('rider@example.com')
        ->assertSee('Saved addresses')
        ->assertSee('Apartment 502')
        ->assertSee('Wishlist Test Gloves')
        ->assertSee('ORD-PROFILE-001');
});

test('rider can update account email from rider profile', function () {
    $user = User::factory()->create([
        'name' => 'Old Rider',
        'email' => 'old-rider@example.com',
    ]);
    $rider = Rider::factory()->for($user)->create([
        'full_name' => 'Old Rider',
        'phone_number' => '+201011111111',
        'current_address' => 'Old Cairo Address',
    ]);

    $this->actingAs($user)
        ->patch(route('rider.profile.update'), [
            'full_name' => 'New Rider',
            'email' => 'new-rider@example.com',
            'date_of_birth' => '1994-01-20',
            'current_address' => 'New Cairo Address',
            'phone_number' => '+201022222222',
            'backup_phone_number' => '',
            'emergency_contact_name' => '',
            'emergency_contact_relationship' => '',
            'emergency_contact_phone' => '',
        ])
        ->assertRedirect(route('rider.profile.edit'))
        ->assertSessionHasNoErrors();

    expect($user->fresh()->name)->toBe('New Rider')
        ->and($user->fresh()->email)->toBe('new-rider@example.com')
        ->and($user->fresh()->email_verified_at)->toBeNull()
        ->and($rider->fresh()->current_address)->toBe('New Cairo Address');
});

test('rider can update password from rider profile', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);
    Rider::factory()->for($user)->create();

    $this->actingAs($user)
        ->patch(route('rider.profile.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect(Hash::check('new-secure-password', $user->fresh()->password))->toBeTrue();
});

test('rider can manage saved addresses', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create();

    $this->actingAs($user)
        ->post(route('rider.profile.addresses.store'), [
            'label' => 'Garage',
            'recipient_name' => 'Garage Rider',
            'phone' => '+201099999999',
            'city' => 'Giza',
            'area' => 'Dokki',
            'street' => 'Workshop Street',
            'building' => '7',
            'floor' => '2',
            'apartment' => '12',
            'landmark' => 'Near bridge',
            'notes' => 'Call first',
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $address = RiderSavedAddress::query()->whereBelongsTo($rider)->firstOrFail();

    expect($address->is_default)->toBeTrue();

    $this->actingAs($user)
        ->patch(route('rider.profile.addresses.update', $address), [
            'label' => 'Updated Garage',
            'recipient_name' => 'Garage Rider',
            'phone' => '+201088888888',
            'city' => 'Cairo',
            'area' => 'Maadi',
            'street' => 'Updated Street',
            'building' => '8',
            'floor' => '3',
            'apartment' => '14',
            'landmark' => 'Near fuel station',
            'notes' => 'Gate code 12',
            'is_default' => '1',
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('rider_saved_addresses', [
        'id' => $address->id,
        'label' => 'Updated Garage',
        'city' => 'Cairo',
        'apartment' => '14',
        'is_default' => true,
    ]);

    $this->actingAs($user)
        ->delete(route('rider.profile.addresses.destroy', $address))
        ->assertRedirect();

    $this->assertDatabaseMissing('rider_saved_addresses', [
        'id' => $address->id,
    ]);
});

test('rider can save and remove wishlist items', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create();
    $product = Product::factory()->create([
        'name' => 'Wishlist Helmet',
    ]);

    $this->actingAs($user)
        ->post(route('rider.wishlist.store', $product))
        ->assertRedirect();

    $wishlistItem = WishlistItem::query()->whereBelongsTo($rider)->whereBelongsTo($product)->firstOrFail();

    $this->assertDatabaseHas('wishlist_items', [
        'id' => $wishlistItem->id,
        'rider_id' => $rider->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($user)
        ->get(route('rider.profile.edit'))
        ->assertOk()
        ->assertSee('Wishlist Helmet');

    $this->actingAs($user)
        ->delete(route('rider.wishlist.destroy', $wishlistItem))
        ->assertRedirect();

    $this->assertDatabaseMissing('wishlist_items', [
        'id' => $wishlistItem->id,
    ]);
});
