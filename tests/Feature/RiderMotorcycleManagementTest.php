<?php

use App\Models\Ad;
use App\Models\Subscription;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);

    $this->withSession(['locale' => 'en']);
    app()->setLocale('en');
});

test('unsubscribed users are redirected to subscription when trying to create ads', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('ads.create'))
        ->assertRedirect(route('subscriptions.show'));
});

test('subscribed users can open the create ad screen', function () {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->create([
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    $this->actingAs($user)
        ->get(route('ads.create'))
        ->assertOk()
        ->assertSee('Create Ad');
});

test('subscribed users can attach multiple images to an ad', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    Subscription::factory()->for($user)->create([
        'plan' => Subscription::PLAN_BUSINESS,
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    $this->actingAs($user)
        ->post(route('ads.store'), [
            'title' => 'BMW R nineT',
            'description' => str_repeat('A', 255),
            'category' => Ad::CATEGORY_MOTORCYCLE,
            'price' => 450000,
            'location' => 'Cairo',
            'condition' => 'used',
            'contact_phone' => '+201011111111',
            'images' => [
                UploadedFile::fake()->image('front.jpg'),
                UploadedFile::fake()->image('side.png'),
            ],
            'status' => Ad::STATUS_PUBLISHED,
        ])
        ->assertRedirect();

    $ad = Ad::query()->whereBelongsTo($user)->firstOrFail();

    expect($ad->images)->toHaveCount(2);

    Storage::disk('public')->assertExists($ad->images[0]);
    Storage::disk('public')->assertExists($ad->images[1]);
});

test('new images are added to existing ad images until the image limit', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    Subscription::factory()->for($user)->create([
        'plan' => Subscription::PLAN_BUSINESS,
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    $ad = Ad::factory()->for($user)->create([
        'title' => 'Ducati Scrambler',
        'images' => ['ads/existing.jpg'],
        'status' => Ad::STATUS_DRAFT,
    ]);

    $this->actingAs($user)
        ->patch(route('ads.update', $ad), [
            'title' => 'Ducati Scrambler',
            'description' => 'Clean bike with recent service.',
            'category' => Ad::CATEGORY_MOTORCYCLE,
            'price' => 380000,
            'location' => 'Alexandria',
            'condition' => 'used',
            'contact_phone' => '+201011111111',
            'images' => [
                UploadedFile::fake()->image('new-angle.jpg'),
            ],
            'status' => Ad::STATUS_DRAFT,
        ])
        ->assertRedirect();

    expect($ad->fresh()->images)->toHaveCount(2)
        ->and($ad->fresh()->images[0])->toBe('ads/existing.jpg');

    $ad->update([
        'images' => [
            'ads/1.jpg',
            'ads/2.jpg',
            'ads/3.jpg',
            'ads/4.jpg',
            'ads/5.jpg',
            'ads/6.jpg',
        ],
    ]);

    $this->actingAs($user)
        ->patch(route('ads.update', $ad), [
            'title' => 'Ducati Scrambler',
            'description' => 'Clean bike with recent service.',
            'category' => Ad::CATEGORY_MOTORCYCLE,
            'price' => 380000,
            'location' => 'Alexandria',
            'condition' => 'used',
            'contact_phone' => '+201011111111',
            'images' => [
                UploadedFile::fake()->image('too-many.jpg'),
            ],
            'status' => Ad::STATUS_DRAFT,
        ])
        ->assertSessionHasErrors('images');
});

test('ad description cannot exceed two hundred fifty five characters', function () {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->create([
        'status' => Subscription::STATUS_ACTIVE,
    ]);

    $this->actingAs($user)
        ->post(route('ads.store'), [
            'title' => 'Too Long Description',
            'description' => str_repeat('A', 256),
            'category' => Ad::CATEGORY_PART,
            'price' => 1200,
            'location' => 'Giza',
            'condition' => 'new',
            'contact_phone' => '+201011111111',
            'status' => Ad::STATUS_DRAFT,
        ])
        ->assertSessionHasErrors('description');
});
