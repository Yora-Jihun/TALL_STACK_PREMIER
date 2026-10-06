<?php

use App\Models\User;

it('registers a new user and logs them in', function () {
    $this->post(route('register.store'), [
        'name' => 'Ana Cruz',
        'email' => 'ana@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'ana@example.com',
    ]);
});

it('rejects an email that is already taken', function () {
    User::factory()->create(['email' => 'ana@example.com']);

    $this->post(route('register.store'), [
        'name' => 'Another Ana',
        'email' => 'ana@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
    expect(User::count())->toBe(1);
});
