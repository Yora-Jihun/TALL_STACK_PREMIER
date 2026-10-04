<?php

use App\Enums\WorkspaceRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

it('has many projects', function () {
    $workspace = Workspace::factory()->create();
    Project::factory()->count(2)->for($workspace)->create();

    expect($workspace->projects)->toHaveCount(2);
});

it('has many tasks through its projects', function () {
    $workspace = Workspace::factory()->create();
    $projects = Project::factory()->count(2)->for($workspace)->create();
    Task::factory()->count(2)->for($projects[0])->create();
    Task::factory()->count(3)->for($projects[1])->create();

    Task::factory()->create();

    expect($workspace->tasks)->toHaveCount(5);
});

it('has members with a role', function () {
    $workspace = Workspace::factory()->create();
    $user = User::factory()->create();

    $workspace->members()->attach($user, ['role' => 'admin']);

    $member = $workspace->members()->first();

    expect($member->is($user))->toBeTrue()
        ->and($member->pivot->role)->toBe(WorkspaceRole::Admin);
});
