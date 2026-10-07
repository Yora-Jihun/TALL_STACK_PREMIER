<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;

class ProjectPolicy
{
    /**
     * Owners and admins may create projects in a workspace.
     */
    public function create(User $user, Workspace $workspace): bool
    {
        return $workspace->roleOf($user)?->canManage() ?? false;
    }

    /**
     * Owners and admins may archive a project.
     */
    public function archive(User $user, Project $project): bool
    {
        return $project->workspace->roleOf($user)?->canManage() ?? false;
    }
}
