<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspaceMember
{
    /**
     * Only members of the workspace in the URL may continue.
     * Everyone else gets a 404, so outsiders can't tell the workspace exists.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $workspace = $request->route('workspace');

        abort_unless(
            $user instanceof User
                && $workspace instanceof Workspace
                && $user->workspaces()->whereKey($workspace->id)->exists(),
            404,
        );

        return $next($request);
    }
}
