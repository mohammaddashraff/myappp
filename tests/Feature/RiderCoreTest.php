<?php

use App\Models\Ad;
use App\Models\Subscription;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
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

test('dashboard shows subscription prompt for unsubscribed users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('dashboard-shell', false)
        ->assertSee('dashboard-stat', false)
        ->assertSee('Home Page')
        ->assertSee('Pay to Unlock')
        ->assertSee('Subscribe to open publishing slots and seller contact visibility.');
});

test('dashboard shows active plan details for subscribed users', function () {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->create([
        'plan' => Subscription::PLAN_BUSINESS,
        'status' => Subscription::STATUS_ACTIVE,
    ]);
    Ad::factory()->count(2)->for($user)->create([
        'status' => Ad::STATUS_PUBLISHED,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Business')
        ->assertSee('Active')
        ->assertSee('2');
});

test('admin can monitor subscriptions without approval actions', function () {
    $this->seed(AdminUserSeeder::class);

    $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.subscriptions.index'))
        ->assertOk()
        ->assertSee('Subscriptions')
        ->assertSee('Payment');
});

test('locale switch applies translated labels across the dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('dashboard'))
        ->get(route('locale.switch', ['locale' => 'ar']))
        ->assertRedirect(route('dashboard'));

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('الصفحة الرئيسية')
        ->assertSee('الاشتراك');
});
