<?php

use App\Actions\CreateWorkspace;
use App\Enums\WorkspaceRole;
use App\Models\User;

it('creates a workspace with the given name', function () {
    $user = User::factory()->create();

    $workspace = app(CreateWorkspace::class)->handle($user, 'Acme Studio');

    expect($workspace->name)->toBe('Acme Studio');
    $this->assertDatabaseHas('workspaces', ['name' => 'Acme Studio']);
});

it('makes the creator the owner', function () {
    $user = User::factory()->create();

    $workspace = app(CreateWorkspace::class)->handle($user, 'Acme Studio');

    $member = $workspace->members()->first();

    expect($member->is($user))->toBeTrue()
        ->and($member->pivot->role)->toBe(WorkspaceRole::Owner);
});

it('makes the slug from the name', function () {
    $user = User::factory()->create();

    $workspace = app(CreateWorkspace::class)->handle($user, 'Acme Studio');

    expect($workspace->slug)->toBe('acme-studio');
});

it('adds a number when the slug is taken', function () {
    $user = User::factory()->create();
    app(CreateWorkspace::class)->handle($user, 'Acme Studio');

    $second = app(CreateWorkspace::class)->handle($user, 'Acme Studio');

    expect($second->slug)->toBe('acme-studio-2');
});

it('falls back to "workspace" when the name has no letters', function () {
    $user = User::factory()->create();

    $workspace = app(CreateWorkspace::class)->handle($user, '!!!');

    expect($workspace->slug)->toBe('workspace');
});
