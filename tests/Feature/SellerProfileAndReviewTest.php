<?php

use App\Models\Ad;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserReview;
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

test('seller profile shows current ads past ads and customer reviews', function () {
    $viewer = User::factory()->create();
    $seller = User::factory()->create(['name' => 'Trusted Seller']);
    $reviewer = User::factory()->create(['name' => 'Happy Buyer']);

    Ad::factory()->for($seller)->create([
        'title' => 'Kawasaki Ninja 400',
        'status' => Ad::STATUS_PUBLISHED,
    ]);

    Ad::factory()->for($seller)->create([
        'title' => 'Sold Honda Hornet',
        'status' => Ad::STATUS_SOLD,
        'sold_at' => now()->subDay(),
    ]);

    UserReview::factory()->create([
        'reviewer_id' => $reviewer->id,
        'reviewed_user_id' => $seller->id,
        'rating' => 5,
        'comment' => 'Smooth deal and fast replies.',
    ]);

    $this->actingAs($viewer)
        ->get(route('sellers.show', $seller))
        ->assertOk()
        ->assertSee('Trusted Seller')
        ->assertSee('Current Ads')
        ->assertSee('Kawasaki Ninja 400')
        ->assertSee('Past Ads')
        ->assertSee('Sold Honda Hornet')
        ->assertSee('Happy Buyer')
        ->assertSee('Smooth deal and fast replies.')
        ->assertSee('Submit Review');
});

test('users can review a seller and the rating appears on ads', function () {
    $viewer = User::factory()->create();
    Subscription::factory()->for($viewer)->create([
        'plan' => Subscription::PLAN_INDIVIDUAL,
        'status' => Subscription::STATUS_ACTIVE,
    ]);
    $seller = User::factory()->create(['name' => 'Rated Seller']);

    Ad::factory()->for($seller)->create([
        'title' => 'Suzuki GSX',
        'status' => Ad::STATUS_PUBLISHED,
    ]);

    $this->actingAs($viewer)
        ->post(route('sellers.reviews.store', $seller), [
            'rating' => 4,
            'comment' => 'Good communication.',
        ])
        ->assertRedirect(route('sellers.show', $seller));

    expect(UserReview::query()
        ->where('reviewer_id', $viewer->id)
        ->where('reviewed_user_id', $seller->id)
        ->value('rating'))->toBe(4);

    $this->actingAs($viewer)
        ->get(route('ads.index'))
        ->assertOk()
        ->assertSee('Rated Seller')
        ->assertSee('4.0 / 5');
});

test('users cannot review themselves', function () {
    $seller = User::factory()->create();

    $this->actingAs($seller)
        ->post(route('sellers.reviews.store', $seller), [
            'rating' => 5,
            'comment' => 'I am great.',
        ])
        ->assertSessionHasErrors('rating');
});

test('individual subscribers can upgrade to business through test checkout', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->for($user)->create([
        'plan' => Subscription::PLAN_INDIVIDUAL,
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    $this->actingAs($user)
        ->post(route('subscriptions.store'), [
            'plan' => Subscription::PLAN_BUSINESS,
        ])
        ->assertRedirect(route('subscriptions.checkout'));

    expect($subscription->fresh()->plan)->toBe(Subscription::PLAN_INDIVIDUAL);

    $this->actingAs($user)
        ->post(route('subscriptions.pay'), [
            'cardholder_name' => 'Test User',
            'card_number' => '4242424242424242',
            'expiry_month' => '12',
            'expiry_year' => '30',
            'cvv' => '123',
        ])
        ->assertRedirect(route('subscriptions.show'));

    expect($subscription->fresh()->plan)->toBe(Subscription::PLAN_BUSINESS)
        ->and($subscription->fresh()->status)->toBe(Subscription::STATUS_ACTIVE);
});
