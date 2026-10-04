<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\Workspace;

it('belongs to a workspace', function () {
    $workspace = Workspace::factory()->create();
    $project = Project::factory()->for($workspace)->create();

    expect($project->workspace->is($workspace))->toBeTrue();
});

it('has many tasks', function () {
    $project = Project::factory()->create();
    Task::factory()->count(3)->for($project)->create();

    expect($project->tasks)->toHaveCount(3);
});

it('can be archived', function () {
    $project = Project::factory()->archived()->create();

    expect($project->archived_at)->not->toBeNull();

});
