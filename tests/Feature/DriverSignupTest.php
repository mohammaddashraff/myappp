<?php

use App\Models\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function validDriverIdentityPayload(array $overrides = []): array
{
    return array_merge([
        'legal_name' => 'Ahmed Mohamed Hassan',
        'date_of_birth' => '1994-05-12',
        'current_address' => '15 Tahrir Street, Dokki, Giza',
    ], $overrides);
}

function validDriverContactPayload(array $overrides = []): array
{
    return array_merge([
        'phone_number' => '+201012345678',
        'backup_phone_number' => '+201112345678',
        'emergency_contact_name' => 'Mona Hassan',
        'emergency_contact_relationship' => 'Sister',
        'emergency_contact_phone' => '+201222222222',
    ], $overrides);
}

function validDriverVehiclePayload(array $overrides = []): array
{
    return array_merge([
        'plate_number' => 'GZ-2458',
        'vehicle_owner_name' => 'Ahmed Mohamed Hassan',
        'chassis_number' => 'CHS123456789',
        'motor_number' => 'MTR987654321',
    ], $overrides);
}

function validDriverReviewPayload(array $overrides = []): array
{
    return array_merge([
        'consented_to_background_check' => '1',
        'accepted_terms' => '1',
    ], $overrides);
}

test('driver signup starts on the identity step', function () {
    $this->get(route('drivers.signup.create'))
        ->assertRedirect(route('drivers.signup.step', 'identity'));

    $response = $this->get(route('drivers.signup.step', 'identity'));

    $response
        ->assertOk()
        ->assertSee('Step 1: Legal identity')
        ->assertSee('Full legal name');
});

test('driver can complete multi step signup without optional photos', function () {
    $this->post(route('drivers.signup.step.store', 'identity'), validDriverIdentityPayload())
        ->assertRedirect(route('drivers.signup.step', 'contact'));

    $this->post(route('drivers.signup.step.store', 'contact'), validDriverContactPayload())
        ->assertRedirect(route('drivers.signup.step', 'documents'));

    $this->post(route('drivers.signup.step.store', 'documents'), [])
        ->assertRedirect(route('drivers.signup.step', 'vehicle'));

    $this->post(route('drivers.signup.step.store', 'vehicle'), validDriverVehiclePayload())
        ->assertRedirect(route('drivers.signup.step', 'review'));

    $this->post(route('drivers.signup.step.store', 'review'), validDriverReviewPayload())
        ->assertRedirect(route('drivers.signup.success'));

    $this->assertDatabaseHas('drivers', [
        'legal_name' => 'Ahmed Mohamed Hassan',
        'phone_number' => '+201012345678',
        'approval_status' => 'pending',
        'national_id_front_photo_path' => null,
        'vehicle_front_photo_path' => null,
    ]);
});

test('uploaded driver photos are stored in a per-driver local folder', function () {
    Storage::fake(Driver::photoDisk());

    $this->post(route('drivers.signup.step.store', 'identity'), validDriverIdentityPayload())
        ->assertRedirect(route('drivers.signup.step', 'contact'));

    $this->post(route('drivers.signup.step.store', 'contact'), validDriverContactPayload())
        ->assertRedirect(route('drivers.signup.step', 'documents'));

    $this->post(route('drivers.signup.step.store', 'documents'), [
        'national_id_front_photo' => UploadedFile::fake()->image('id-front.jpg'),
    ])->assertRedirect(route('drivers.signup.step', 'vehicle'));

    $this->post(route('drivers.signup.step.store', 'vehicle'), validDriverVehiclePayload([
        'vehicle_front_photo' => UploadedFile::fake()->image('vehicle-front.jpg'),
    ]))->assertRedirect(route('drivers.signup.step', 'review'));

    $this->post(route('drivers.signup.step.store', 'review'), validDriverReviewPayload())
        ->assertRedirect(route('drivers.signup.success'));

    $driver = Driver::firstOrFail();

    expect($driver->national_id_front_photo_path)->not->toBeNull()
        ->and($driver->vehicle_front_photo_path)->not->toBeNull()
        ->and(Str::contains($driver->national_id_front_photo_path, 'driver-'.$driver->id.'-ahmed-mohamed-hassan'))->toBeTrue();

    Storage::disk(Driver::photoDisk())->assertExists($driver->national_id_front_photo_path);
    Storage::disk(Driver::photoDisk())->assertExists($driver->vehicle_front_photo_path);
});
