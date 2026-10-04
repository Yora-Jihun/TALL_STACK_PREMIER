<?php

namespace Database\Seeders;

use App\Enums\WorkspaceRole;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $owner = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Workspace::factory()
            ->count(2)
            ->create()
            ->each(function (Workspace $workspace) use ($owner) {
                $admin = User::factory()->create();
                $members = User::factory()->count(3)->create();

                $workspace->members()->attach($owner, ['role' => WorkspaceRole::Owner]);
                $workspace->members()->attach($admin, ['role' => WorkspaceRole::Admin]);
                $workspace->members()->attach($members, ['role' => WorkspaceRole::Member]);

                $team = $members->concat([$owner, $admin]);

                Project::factory()
                    ->count(3)
                    ->for($workspace)
                    ->recycle($team)
                    ->has(
                        Task::factory()
                            ->count(5)
                            ->state(fn () => ['assignee_id' => $team->random()->id])
                            ->has(Comment::factory()->count(2))
                    )
                    ->create();

                Project::factory()->archived()->for($workspace)->create();
            });
    }
}
