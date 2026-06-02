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
        ->assertSee('Your application is being reviewed')
        ->assertSee('Application #'.$driver->id)
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

test('authenticated driver can view the placeholder dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('drivers.dashboard'))
        ->assertOk()
        ->assertSee('Your driver dashboard is being prepared');
});
