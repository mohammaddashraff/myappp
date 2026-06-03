<?php

use App\Models\Motorcycle;
use App\Models\MotorcycleBrand;
use App\Models\MotorcycleModel;
use App\Models\Rider;
use App\Models\User;
use Database\Seeders\MotorcycleBrandSeeder;
use Database\Seeders\MotorcycleModelSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    $this->seed([
        MotorcycleBrandSeeder::class,
        MotorcycleModelSeeder::class,
    ]);
});

test('rider can add a motorcycle with images and see it in the garage', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    Rider::factory()->for($user)->create();

    $brand = MotorcycleBrand::query()->where('name', 'SYM')->firstOrFail();
    $model = MotorcycleModel::query()
        ->where('brand_id', $brand->id)
        ->where('name', 'Orbit')
        ->firstOrFail();

    $this->actingAs($user)
        ->post(route('rider.motorcycles.store'), [
            'type' => 'scooter',
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'year' => 2024,
            'engine_cc' => 150,
            'plate_number' => 'RID-1001',
            'color' => 'Blue',
            'image' => UploadedFile::fake()->image('bike.jpg'),
            'ownership_license_image' => UploadedFile::fake()->image('ownership.jpg'),
            'motorcycle_registration_image' => UploadedFile::fake()->image('registration.jpg'),
        ])
        ->assertRedirect(route('rider.garage'))
        ->assertSessionHas('status', 'motorcycle-added');

    $motorcycle = Motorcycle::query()->where('plate_number', 'RID-1001')->firstOrFail();

    expect($motorcycle->rider->user_id)->toBe($user->id)
        ->and($motorcycle->brand)->toBe('SYM')
        ->and($motorcycle->model)->toBe('Orbit')
        ->and($motorcycle->type)->toBe('scooter');

    Storage::disk('public')->assertExists($motorcycle->image);
    Storage::disk('public')->assertExists($motorcycle->ownership_license_image);
    Storage::disk('public')->assertExists($motorcycle->motorcycle_registration_image);

    $this->actingAs($user)
        ->get(route('rider.garage'))
        ->assertOk()
        ->assertSee('RID-1001')
        ->assertSee('SYM')
        ->assertSee('Orbit');
});

test('rider motorcycle form rejects unsupported three wheel types', function () {
    $user = User::factory()->create();
    Rider::factory()->for($user)->create();

    $brand = MotorcycleBrand::query()->where('name', 'TVS')->firstOrFail();
    $model = MotorcycleModel::query()
        ->where('brand_id', $brand->id)
        ->where('name', 'HLX')
        ->firstOrFail();

    $this->actingAs($user)
        ->post(route('rider.motorcycles.store'), [
            'type' => 'tricycle',
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'year' => 2023,
            'engine_cc' => 150,
            'plate_number' => 'RID-1002',
        ])
        ->assertSessionHasErrors(['type']);

    $this->assertDatabaseMissing('motorcycles', [
        'plate_number' => 'RID-1002',
    ]);
});

test('rider can update his motorcycle details', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create();

    $brand = MotorcycleBrand::query()->where('name', 'Honda')->firstOrFail();
    $oldModel = MotorcycleModel::query()
        ->where('brand_id', $brand->id)
        ->where('name', 'CBR')
        ->firstOrFail();
    $newModel = MotorcycleModel::query()
        ->where('brand_id', $brand->id)
        ->where('name', 'PCX')
        ->firstOrFail();

    $motorcycle = Motorcycle::factory()->for($rider)->create([
        'brand_id' => $brand->id,
        'model_id' => $oldModel->id,
        'brand' => 'Honda',
        'model' => 'CBR',
        'type' => 'sport',
        'plate_number' => 'RID-2001',
        'engine_cc' => 150,
        'color' => 'Black',
    ]);

    $this->actingAs($user)
        ->patch(route('rider.motorcycles.update', $motorcycle), [
            'type' => 'scooter',
            'brand_id' => $brand->id,
            'model_id' => $newModel->id,
            'year' => 2025,
            'engine_cc' => 160,
            'plate_number' => 'RID-2001',
            'color' => 'White',
        ])
        ->assertRedirect(route('rider.garage'))
        ->assertSessionHas('status', 'motorcycle-updated');

    $this->assertDatabaseHas('motorcycles', [
        'id' => $motorcycle->id,
        'model_id' => $newModel->id,
        'model' => 'PCX',
        'type' => 'scooter',
        'year' => 2025,
        'engine_cc' => 160,
        'color' => 'White',
    ]);
});

test('rider can delete his motorcycle', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create();

    $motorcycle = Motorcycle::factory()->for($rider)->create([
        'plate_number' => 'RID-3001',
        'image' => UploadedFile::fake()->image('bike.jpg')->store('motorcycles', 'public'),
    ]);

    $this->actingAs($user)
        ->delete(route('rider.motorcycles.destroy', $motorcycle))
        ->assertRedirect(route('rider.garage'))
        ->assertSessionHas('status', 'motorcycle-deleted');

    $this->assertDatabaseMissing('motorcycles', [
        'id' => $motorcycle->id,
    ]);
});

test('rider cannot view or edit another riders motorcycle', function () {
    $owner = User::factory()->create();
    $ownerRider = Rider::factory()->for($owner)->create();
    $intruder = User::factory()->create();
    Rider::factory()->for($intruder)->create();

    $motorcycle = Motorcycle::factory()->for($ownerRider)->create([
        'plate_number' => 'RID-4001',
    ]);

    $this->actingAs($intruder)
        ->get(route('rider.motorcycles.show', $motorcycle))
        ->assertNotFound();

    $this->actingAs($intruder)
        ->get(route('rider.motorcycles.edit', $motorcycle))
        ->assertNotFound();
});

test('brand models endpoint returns models for the selected brand only', function () {
    $user = User::factory()->create();
    Rider::factory()->for($user)->create();

    $brand = MotorcycleBrand::query()->where('name', 'Yamaha')->firstOrFail();

    $this->actingAs($user)
        ->getJson(route('rider.motorcycle-brands.models', $brand))
        ->assertOk()
        ->assertJsonFragment(['name' => 'R15'])
        ->assertJsonMissing(['name' => 'Orbit']);
});
