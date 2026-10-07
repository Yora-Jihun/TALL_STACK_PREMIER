<?php

use App\Enums\WorkspaceRole;
use App\Models\User;
use App\Models\Workspace;

it('lets only the owner delete the workspace', function (?WorkspaceRole $role, bool $allowed) {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    if ($role !== null) {
        $workspace->members()->attach($user, ['role' => $role]);
    }

    expect($user->can('delete', $workspace))->toBe($allowed);
})->with([
    'owner' => [WorkspaceRole::Owner, true],
    'admin' => [WorkspaceRole::Admin, false],
    'member' => [WorkspaceRole::Member, false],
    'outsider' => [null, false],
]);

it('lets owners and admins invite people', function (?WorkspaceRole $role, bool $allowed) {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    if ($role !== null) {
        $workspace->members()->attach($user, ['role' => $role]);
    }

    expect($user->can('invite', $workspace))->toBe($allowed);
})->with([
    'owner' => [WorkspaceRole::Owner, true],
    'admin' => [WorkspaceRole::Admin, true],
    'member' => [WorkspaceRole::Member, false],
    'outsider' => [null, false],
]);

it('lets only the owner change roles', function (?WorkspaceRole $role, bool $allowed) {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    if ($role !== null) {
        $workspace->members()->attach($user, ['role' => $role]);
    }

    expect($user->can('changeRoles', $workspace))->toBe($allowed);
})->with([
    'owner' => [WorkspaceRole::Owner, true],
    'admin' => [WorkspaceRole::Admin, false],
    'member' => [WorkspaceRole::Member, false],
    'outsider' => [null, false],
]);
