<?php

use App\Enums\WorkspaceRole;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;

it('lets owners and admins create projects', function (?WorkspaceRole $role, bool $allowed) {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();

    if ($role !== null) {
        $workspace->members()->attach($user, ['role' => $role]);
    }

    expect($user->can('create', [Project::class, $workspace]))->toBe($allowed);
})->with([
    'owner' => [WorkspaceRole::Owner, true],
    'admin' => [WorkspaceRole::Admin, true],
    'member' => [WorkspaceRole::Member, false],
    'outsider' => [null, false],
]);

it('lets owners and admins archive projects', function (?WorkspaceRole $role, bool $allowed) {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->for($workspace)->create();

    if ($role !== null) {
        $workspace->members()->attach($user, ['role' => $role]);
    }

    expect($user->can('archive', $project))->toBe($allowed);
})->with([
    'owner' => [WorkspaceRole::Owner, true],
    'admin' => [WorkspaceRole::Admin, true],
    'member' => [WorkspaceRole::Member, false],
    'outsider' => [null, false],
]);
