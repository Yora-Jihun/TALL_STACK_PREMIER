<?php

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;

it('belongs to a task', function () {
    $task = Task::factory()->create();
    $comment = Comment::factory()->for($task)->create();

    expect($comment->task->is($task))->toBeTrue();
});

it('belongs to an author', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->for($user, 'author')->create();

    expect($comment->author->is($user))->toBeTrue();
});
