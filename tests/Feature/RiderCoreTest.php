<?php

use App\Models\Driver;
use App\Models\Motorcycle;
use App\Models\MotorcycleDocument;
use App\Models\Rider;
use App\Models\User;

test('home page starts the rider flow', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('مساحة الراكب الجديدة')
        ->assertSee('ابدأ كراكب')
        ->assertSee(route('register'), false)
        ->assertDontSee('سجل كسائق');
});

test('rider can switch the site language', function () {
    $this->from(route('home'))
        ->get(route('locale.switch', 'en'))
        ->assertRedirect(route('home'))
        ->assertSessionHas('locale', 'en');

    $this->get(route('locale.switch', 'fr'))
        ->assertNotFound();
});

test('home page can render in english', function () {
    $this->withSession(['locale' => 'en'])
        ->get(route('home'))
        ->assertOk()
        ->assertSee('The new rider space')
        ->assertSee('Start as a rider')
        ->assertSee('العربية')
        ->assertDontSee('مساحة الراكب الجديدة');
});

test('default dashboard route redirects to rider dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('rider.dashboard'));
});

test('rider can view their dashboard summary', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create([
        'full_name' => 'Ahmed Rider',
    ]);

    $motorcycle = Motorcycle::factory()->for($rider)->create([
        'plate_number' => 'RD-1001',
    ]);

    MotorcycleDocument::factory()->for($motorcycle)->create();

    $this->actingAs($user)
        ->get(route('rider.dashboard'))
        ->assertOk()
        ->assertSee('Ahmed Rider')
        ->assertSee('الموتوسيكلات')
        ->assertSee('التوصيل الاختياري')
        ->assertSee('1');
});

test('authenticated rider dashboard can render in english', function () {
    $user = User::factory()->create();

    Rider::factory()->for($user)->create([
        'full_name' => 'Ahmed Rider',
    ]);

    $this->actingAs($user)
        ->withSession(['locale' => 'en'])
        ->get(route('rider.dashboard'))
        ->assertOk()
        ->assertSee('Hello Ahmed Rider')
        ->assertSee('Optional work opportunities')
        ->assertSee('Add your first motorcycle')
        ->assertSee('Delivery is not required')
        ->assertSee('العربية');
});

test('rider can open the edit profile form', function () {
    $user = User::factory()->create();

    Rider::factory()->for($user)->create([
        'full_name' => 'Ahmed Rider',
        'phone_number' => '+201011111111',
        'current_address' => 'Cairo',
    ]);

    $this->actingAs($user)
        ->get(route('rider.profile.edit'))
        ->assertOk()
        ->assertSee('تعديل بيانات الراكب')
        ->assertSee('Ahmed Rider')
        ->assertSee('+201011111111')
        ->assertSee('Cairo');
});

test('rider can update their profile', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
    ]);

    $rider = Rider::factory()->for($user)->create([
        'full_name' => 'Old Name',
        'phone_number' => '+201011111111',
        'current_address' => 'Old Address',
        'profile_completed_at' => null,
    ]);

    $this->actingAs($user)
        ->patch(route('rider.profile.update'), [
            'full_name' => 'Updated Rider',
            'date_of_birth' => '1995-05-12',
            'current_address' => 'Updated Cairo Address',
            'phone_number' => '+201022222222',
            'backup_phone_number' => '+201133333333',
            'emergency_contact_name' => 'Mona Rider',
            'emergency_contact_relationship' => 'Sister',
            'emergency_contact_phone' => '+201244444444',
        ])
        ->assertRedirect(route('rider.dashboard'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('riders', [
        'id' => $rider->id,
        'full_name' => 'Updated Rider',
        'current_address' => 'Updated Cairo Address',
        'phone_number' => '+201022222222',
        'backup_phone_number' => '+201133333333',
        'emergency_contact_name' => 'Mona Rider',
        'emergency_contact_relationship' => 'Sister',
        'emergency_contact_phone' => '+201244444444',
    ]);

    expect($rider->fresh()->profile_completed_at)->not->toBeNull()
        ->and($user->fresh()->name)->toBe('Updated Rider');
});

test('rider profile update validates required core fields', function () {
    $user = User::factory()->create();

    Rider::factory()->for($user)->create();

    $this->actingAs($user)
        ->patch(route('rider.profile.update'), [
            'full_name' => '',
            'current_address' => '',
            'phone_number' => '',
        ])
        ->assertSessionHasErrors([
            'full_name',
            'current_address',
            'phone_number',
        ]);
});

test('delivery application is not the next step before adding a motorcycle', function () {
    $user = User::factory()->create();

    Rider::factory()->for($user)->create([
        'full_name' => 'Ahmed Rider',
    ]);

    $this->actingAs($user)
        ->get(route('rider.dashboard'))
        ->assertOk()
        ->assertSee('فرص العمل الاختيارية')
        ->assertSee('الخطوة التالية')
        ->assertSee('أضف أول موتوسيكل')
        ->assertSee('التوصيل ليس مطلوبا')
        ->assertSee('افتح الجراج')
        ->assertDontSee('يحتاج بيانات الراكب')
        ->assertDontSee('ابدأ طلب التوصيل')
        ->assertDontSee('التقديم للتوصيل اختياري');
});

test('delivery application is shown as optional after adding a motorcycle', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create();

    Motorcycle::factory()->for($rider)->create();

    $this->actingAs($user)
        ->get(route('rider.dashboard'))
        ->assertOk()
        ->assertSee('فرص العمل الاختيارية')
        ->assertSee('التقديم للتوصيل اختياري')
        ->assertDontSee('ابدأ طلب التوصيل');
});

test('rider can view motorcycles in their garage', function () {
    $user = User::factory()->create();
    $rider = Rider::factory()->for($user)->create();

    Motorcycle::factory()->for($rider)->create([
        'nickname' => 'Work Bike',
        'owner_name' => 'Ahmed Rider',
        'plate_number' => 'RD-2002',
    ]);

    $this->actingAs($user)
        ->get(route('rider.garage'))
        ->assertOk()
        ->assertSee('Work Bike')
        ->assertSee('Ahmed Rider')
        ->assertSee('RD-2002');
});

test('legacy driver records can be backfilled into rider core tables', function () {
    $user = User::factory()->create();

    Driver::factory()->for($user)->create([
        'legal_name' => 'Legacy Driver',
        'phone_number' => '+201099999999',
        'vehicle_owner_name' => 'Legacy Owner',
        'plate_number' => 'LG-3003',
        'chassis_number' => 'LGCHS3003',
        'motor_number' => 'LGMTR3003',
    ]);

    $migration = require database_path('migrations/2026_06_03_085659_backfill_riders_from_drivers.php');

    $migration->up();

    $this->assertDatabaseHas('riders', [
        'user_id' => $user->id,
        'full_name' => 'Legacy Driver',
        'phone_number' => '+201099999999',
    ]);

    $rider = Rider::whereBelongsTo($user)->firstOrFail();

    $this->assertDatabaseHas('motorcycles', [
        'rider_id' => $rider->id,
        'owner_name' => 'Legacy Owner',
        'plate_number' => 'LG-3003',
        'chassis_number' => 'LGCHS3003',
        'motor_number' => 'LGMTR3003',
        'is_primary' => true,
    ]);
});
