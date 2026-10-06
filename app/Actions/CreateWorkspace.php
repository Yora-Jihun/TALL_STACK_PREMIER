<?php

namespace App\Actions;

use App\Enums\WorkspaceRole;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreateWorkspace
{
    public function handle(User $owner, string $name): Workspace
    {
        return DB::transaction(function () use ($owner, $name): Workspace {
            $workspace = new Workspace(['name' => $name]);
            $workspace->slug = $this->uniqueSlug($name);
            $workspace->save();

            $workspace->members()->attach($owner, ['role' => WorkspaceRole::Owner]);

            return $workspace;
        });
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = 'workspace';
        }

        $slug = $base;
        $number = 2;

        while (Workspace::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$number;
            $number++;
        }

        return $slug;
    }
}
