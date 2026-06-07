<?php

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

test('authenticated user can still open and update the shared profile page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk();

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ])
        ->assertRedirect(route('profile.edit'));

    expect($user->fresh()->name)->toBe('Updated User');
});
