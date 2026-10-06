<?php

use App\Models\User;

it('redirects guests from the dashboard to login', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

it('shows the login screen', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Log in');
});

it('logs in with the right password', function () {
    // Arrange: a user who exists
    $user = User::factory()->create();

    // Act: submit the login form
    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    // Assert: now logged in, as THIS user
    $this->assertAuthenticatedAs($user);
});

it('rejects a wrong password', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect('/');

    $this->assertGuest();
});

it('blocks the 6th login attempt within a minute', function () {
    $user = User::factory()->create();

    // 5 attempts are allowed (wrong password, so each just shows an error)
    foreach (range(1, 5) as $attempt) {
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');
    }

    // the 6th is refused before Fortify even checks the password
    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertTooManyRequests();
});
