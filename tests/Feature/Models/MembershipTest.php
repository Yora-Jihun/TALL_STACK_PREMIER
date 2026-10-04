<?php

use App\Enums\WorkspaceRole;
use App\Models\Membership;
use App\Models\User;
use App\Models\Workspace;

it('belongs to a workspace and a user', function () {
    $workspace = Workspace::factory()->create();
    $user = User::factory()->create();

    $membership = Membership::factory()
        ->for($workspace)
        ->for($user)
        ->create();

    expect($membership->workspace->is($workspace))->toBeTrue()
        ->and($membership->user->is($user))->toBeTrue();
});

it('defaults to the member role', function () {
    $membership = new Membership;

    expect($membership->role)->toBe(WorkspaceRole::Member);
});

it('can be made an owner', function () {
    $membership = Membership::factory()->owner()->create();

    expect($membership->role)->toBe(WorkspaceRole::Owner);
});

it('lets a user belong to many workspaces', function () {
    $user = User::factory()->create();
    $workspaceA = Workspace::factory()->create();
    $workspaceB = Workspace::factory()->create();
    Workspace::factory()->create();

    $user->workspaces()->attach($workspaceA);
    $user->workspaces()->attach($workspaceB);

    expect($user->workspaces)->toHaveCount(2);
});
