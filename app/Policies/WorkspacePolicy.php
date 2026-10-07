<?php

namespace App\Policies;

use App\Enums\WorkspaceRole;
use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    /**
     * Owners and admins may invite people.
     */
    public function invite(User $user, Workspace $workspace): bool
    {
        return $workspace->roleOf($user)?->canManage() ?? false;
    }

    /**
     * Only the owner may change someone's role.
     */
    public function changeRoles(User $user, Workspace $workspace): bool
    {
        return $workspace->roleOf($user) === WorkspaceRole::Owner;
    }

    /**
     * Only the owner may delete the workspace.
     */
    public function delete(User $user, Workspace $workspace): bool
    {
        return $workspace->roleOf($user) === WorkspaceRole::Owner;
    }
}
