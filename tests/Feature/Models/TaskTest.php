<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

it('belongs to a project', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->for($project)->create();

    expect($task->project->is($project))->toBeTrue();
});

it('belongs to a creator and an assignee', function () {
    $creator = User::factory()->create();
    $assignee = User::factory()->create();

    $task = Task::factory()
        ->for($creator, 'creator')
        ->assignedTo($assignee)
        ->create();

    expect($task->creator->is($creator))->toBeTrue()
        ->and($task->assignee->is($assignee))->toBeTrue();
});

it('has many comments', function () {
    $task = Task::factory()->create();
    Comment::factory()->count(2)->for($task)->create();

    expect($task->comments)->toHaveCount(2);
});

it('defaults to todo and medium priority when created without them', function () {
    $project = Project::factory()->create();

    $task = $project->tasks()->create(['title' => 'Write tests']);

    expect($task->status)->toBe(TaskStatus::Todo)
        ->and($task->priority)->toBe(TaskPriority::Medium);
});
