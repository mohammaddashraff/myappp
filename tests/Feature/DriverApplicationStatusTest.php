<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);
});

test('legacy driver dashboard endpoints are retired', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/drivers/dashboard')->assertNotFound();
    $this->actingAs($user)->get('/drivers/application/status')->assertNotFound();
});
