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

test('legacy driver signup flow is retired', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/drivers/signup')->assertNotFound();
    $this->actingAs($user)->get('/drivers/signup/account')->assertNotFound();
});
