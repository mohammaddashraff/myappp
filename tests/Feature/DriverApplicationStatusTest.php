<?php

use App\Models\Driver;
use App\Models\User;

test('pending driver can view application status page', function () {
    $user = User::factory()->create();
    $driver = Driver::factory()->for($user)->create([
        'approval_status' => 'pending',
        'legal_name' => 'Ahmed Mohamed Hassan',
    ]);

    $response = $this->actingAs($user)->get(route('drivers.application.status'));

    $response
        ->assertOk()
        ->assertSee('طلبك قيد المراجعة')
        ->assertSee('طلب رقم #'.$driver->id)
        ->assertSee('Ahmed Mohamed Hassan');
});

test('approved driver is redirected home from application status page', function () {
    $user = User::factory()->create();

    Driver::factory()->for($user)->create([
        'approval_status' => 'approved',
    ]);

    $this->actingAs($user)
        ->get(route('drivers.application.status'))
        ->assertRedirect(route('drivers.dashboard'));
});

test('user without driver application is redirected home from status page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('drivers.application.status'))
        ->assertRedirect(route('drivers.dashboard'));
});

test('authenticated user without application can view the placeholder dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('drivers.dashboard'))
        ->assertOk()
        ->assertSee('لوحة السائق الخاصة بك قيد التجهيز')
        ->assertSee('لا يوجد طلب سائق مرتبط بهذا الحساب حتى الآن.');
});

test('driver dashboard shows the linked application status', function () {
    $user = User::factory()->create();

    Driver::factory()->for($user)->create([
        'approval_status' => 'pending',
        'legal_name' => 'Ahmed Mohamed Hassan',
        'plate_number' => 'GZ-2458',
    ]);

    $this->actingAs($user)
        ->get(route('drivers.dashboard'))
        ->assertOk()
        ->assertSee('حالة الطلب')
        ->assertSee('قيد المراجعة')
        ->assertSee('Ahmed Mohamed Hassan')
        ->assertSee('GZ-2458');
});
