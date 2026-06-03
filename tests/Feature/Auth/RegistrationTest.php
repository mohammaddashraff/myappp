<?php

use App\Models\Rider;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('rider.dashboard', absolute: false));

    $this->assertDatabaseHas('riders', [
        'full_name' => 'Test User',
    ]);

    expect(Rider::where('full_name', 'Test User')->first()?->user?->email)
        ->toBe('test@example.com');
});
