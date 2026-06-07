<?php

use App\Models\Ad;
use App\Models\AdInteraction;
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

test('unsubscribed user can browse published ads', function () {
    $user = User::factory()->create();

    $seller = User::factory()->create();
    Ad::factory()->for($seller)->create([
        'title' => 'Honda PCX 160',
        'status' => Ad::STATUS_PUBLISHED,
    ]);

    $this->actingAs($user)
        ->get(route('ads.index'))
        ->assertOk()
        ->assertSee('Honda PCX 160')
        ->assertSee('Browse Ads')
        ->assertSee('Details locked until subscription')
        ->assertSee('Price')
        ->assertSee('Locked')
        ->assertDontSee('Seller:')
        ->assertDontSee('EGP');
});

test('unsubscribed user cannot see seller contact details', function () {
    $viewer = User::factory()->create();

    $seller = User::factory()->create();
    $ad = Ad::factory()->for($seller)->create([
        'contact_phone' => '+201011122233',
        'status' => Ad::STATUS_PUBLISHED,
    ]);

    $this->actingAs($viewer)
        ->get(route('ads.show', $ad))
        ->assertOk()
        ->assertDontSee('+201011122233')
        ->assertSee('Ad details locked')
        ->assertSee('Price')
        ->assertSee('Category')
        ->assertSee('Location')
        ->assertSee('Condition')
        ->assertSee('Locked')
        ->assertDontSee((string) $ad->price)
        ->assertDontSee($ad->description);
});

test('paid user can reveal seller phone and the click is tracked', function () {
    $viewer = User::factory()->create();
    Subscription::factory()->for($viewer)->create([
        'plan' => Subscription::PLAN_INDIVIDUAL,
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    $seller = User::factory()->create();
    $ad = Ad::factory()->for($seller)->create([
        'contact_phone' => '+201011122233',
        'status' => Ad::STATUS_PUBLISHED,
    ]);

    $this->actingAs($viewer)
        ->get(route('ads.show', $ad))
        ->assertOk()
        ->assertDontSee('+201011122233')
        ->assertSee('Seller Contact')
        ->assertSee('Show phone number');

    $this->actingAs($viewer)
        ->post(route('ads.reveal-phone', $ad))
        ->assertRedirect(route('ads.show', $ad));

    expect(AdInteraction::query()
        ->whereBelongsTo($ad)
        ->whereBelongsTo($viewer)
        ->where('type', AdInteraction::TYPE_VIEW)
        ->count())->toBe(1)
        ->and(AdInteraction::query()
            ->whereBelongsTo($ad)
            ->whereBelongsTo($viewer)
            ->where('type', AdInteraction::TYPE_PHONE_REVEAL)
            ->count())->toBe(1);

    $this->actingAs($viewer)
        ->get(route('ads.show', $ad))
        ->assertOk();

    $this->actingAs($viewer)
        ->post(route('ads.reveal-phone', $ad))
        ->assertRedirect(route('ads.show', $ad));

    expect(AdInteraction::query()
        ->whereBelongsTo($ad)
        ->whereBelongsTo($viewer)
        ->where('type', AdInteraction::TYPE_VIEW)
        ->count())->toBe(1)
        ->and(AdInteraction::query()
            ->whereBelongsTo($ad)
            ->whereBelongsTo($viewer)
            ->where('type', AdInteraction::TYPE_PHONE_REVEAL)
            ->count())->toBe(1);

    $this->actingAs($viewer)
        ->withSession(['revealed_phone_ads' => [$ad->id => true]])
        ->get(route('ads.show', $ad))
        ->assertOk()
        ->assertSee('+201011122233');
});

test('ad analytics do not expose viewer identities', function () {
    $owner = User::factory()->create();
    Subscription::factory()->for($owner)->create([
        'plan' => Subscription::PLAN_BUSINESS,
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    $viewer = User::factory()->create(['name' => 'Private Viewer']);
    Subscription::factory()->for($viewer)->create([
        'plan' => Subscription::PLAN_INDIVIDUAL,
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    $ad = Ad::factory()->for($owner)->create([
        'status' => Ad::STATUS_PUBLISHED,
    ]);

    $this->actingAs($viewer)->get(route('ads.show', $ad))->assertOk();
    $this->actingAs($viewer)->post(route('ads.reveal-phone', $ad))->assertRedirect(route('ads.show', $ad));

    $this->actingAs($owner)
        ->get(route('ads.show', $ad))
        ->assertOk()
        ->assertSee('Ad analytics')
        ->assertSee('Viewer identities are not displayed')
        ->assertDontSee('Private Viewer');
});
