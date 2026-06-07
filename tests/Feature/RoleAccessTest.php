<?php

use App\Models\Subscription;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);

    $this->withSession(['locale' => 'en']);
    app()->setLocale('en');
});

test('new registrations land on the home page and are ready for subscription', function () {
    $this->post(route('register'), [
        'name' => 'New User',
        'email' => 'new-user@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertRedirect(route('dashboard', absolute: false));

    $user = User::query()->where('email', 'new-user@example.com')->firstOrFail();

    expect($user->roles)->toHaveCount(0);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Home Page')
        ->assertSee('Pay to Unlock');
});

test('selected plan redirects to test payment and payment activates subscription', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('subscriptions.store'), [
            'plan' => Subscription::PLAN_INDIVIDUAL,
        ])
        ->assertRedirect(route('subscriptions.checkout'));

    $subscription = $user->fresh()->subscription;

    expect($subscription)->not->toBeNull()
        ->and($subscription->status)->toBe(Subscription::STATUS_INACTIVE);

    $this->actingAs($user)
        ->post(route('subscriptions.pay'), [
            'cardholder_name' => 'Test User',
            'card_number' => '4242424242424242',
            'expiry_month' => '12',
            'expiry_year' => '30',
            'cvv' => '123',
        ])
        ->assertRedirect(route('subscriptions.show'));

    expect($subscription->fresh()->status)->toBe(Subscription::STATUS_ACTIVE)
        ->and($subscription->fresh()->payment_gateway)->toBe('test_gateway')
        ->and($subscription->fresh()->payment_reference)->not->toBeNull();
});

test('retired marketplace and provider routes are no longer accessible', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/rider/marketplace')->assertNotFound();
    $this->actingAs($user)->get('/drivers/signup')->assertNotFound();
    $this->actingAs($user)->get('/seller/dashboard')->assertNotFound();
});
