<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\View\View;

class ShowWorkspaceController extends Controller
{
    public function __invoke(Workspace $workspace): View
    {
        return view('workspaces.show', [
            'workspace' => $workspace,
            'projects' => $workspace->projects()->whereNull('archived_at')->orderBy('name')->get(),
        ]);
    }
}
