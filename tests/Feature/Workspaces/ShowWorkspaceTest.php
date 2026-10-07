<?php

use App\Models\User;
use App\Models\Workspace;

it('shows the workspace to a member', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['name' => 'Acme Studio']);
    $workspace->members()->attach($user);

    $this->actingAs($user)
        ->get(route('workspaces.show', $workspace))
        ->assertOk()
        ->assertSee('Acme Studio');
});

it('returns 404 for someone who is not a member', function () {
    $user = User::factory()->create();
    $mine = Workspace::factory()->create();
    $mine->members()->attach($user);

    $other = Workspace::factory()->create();

    $this->actingAs($user)
        ->get(route('workspaces.show', $other))
        ->assertNotFound();
});

it('sends guests to the login page', function () {
    $workspace = Workspace::factory()->create();

    $this->get(route('workspaces.show', $workspace))
        ->assertRedirect(route('login'));
});

it('returns 404 for a slug that does not exist', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/w/nope')
        ->assertNotFound();
});
