<?php

use App\Models\Ad;
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

function createSubscribedUser(string $plan): User
{
    $user = User::factory()->create();
    Subscription::factory()->for($user)->create([
        'plan' => $plan,
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    return $user;
}

test('individual subscription allows only one active unsold published ad at a time', function () {
    $user = createSubscribedUser(Subscription::PLAN_INDIVIDUAL);

    $this->actingAs($user)
        ->post(route('ads.store'), [
            'title' => 'Yamaha NMAX',
            'description' => 'Clean scooter.',
            'category' => Ad::CATEGORY_MOTORCYCLE,
            'price' => 125000,
            'location' => 'Cairo',
            'condition' => 'used',
            'contact_phone' => '+201011111111',
            'status' => Ad::STATUS_PUBLISHED,
        ])
        ->assertRedirect();

    $this->actingAs($user)
        ->get(route('ads.create'))
        ->assertRedirect(route('subscriptions.show'));

    $ad = Ad::query()->whereBelongsTo($user)->firstOrFail();

    $this->actingAs($user)
        ->patch(route('ads.mark-sold', $ad))
        ->assertRedirect(route('ads.my'));

    $this->actingAs($user)
        ->get(route('ads.create'))
        ->assertOk();
});

test('business subscription allows up to five active unsold ads', function () {
    $user = createSubscribedUser(Subscription::PLAN_BUSINESS);

    foreach (range(1, 5) as $index) {
        $this->actingAs($user)
            ->post(route('ads.store'), [
                'title' => 'Ad '.$index,
                'description' => 'Business listing '.$index,
                'category' => Ad::CATEGORY_PART,
                'price' => 1000 + $index,
                'location' => 'Giza',
                'condition' => 'new',
                'contact_phone' => '+201011111111',
                'status' => Ad::STATUS_PUBLISHED,
            ])
            ->assertRedirect();
    }

    expect($user->fresh()->publishedUnsoldAdsCount())->toBe(5);

    $this->actingAs($user)
        ->get(route('ads.create'))
        ->assertRedirect(route('subscriptions.show'));
});

test('only ad owner can edit or mark ad as sold', function () {
    $owner = createSubscribedUser(Subscription::PLAN_BUSINESS);
    $otherUser = createSubscribedUser(Subscription::PLAN_BUSINESS);

    $ad = Ad::factory()->for($owner)->create();

    $this->actingAs($otherUser)
        ->get(route('ads.edit', $ad))
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->patch(route('ads.mark-sold', $ad))
        ->assertForbidden();
});
