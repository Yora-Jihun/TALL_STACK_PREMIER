<?php

namespace App\Livewire\Workspaces;

use App\Actions\CreateWorkspace;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateWorkspaceForm extends Component
{
    #[Validate('required|string|min:3|max:50')]
    public string $name = '';

    public function save(CreateWorkspace $createWorkspace): void
    {
        $this->validate();

        $user = auth()->user();
        abort_unless($user instanceof User, 403);

        $workspace = $createWorkspace->handle($user, $this->name);

        $this->redirectRoute('workspaces.show', $workspace, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.workspaces.create-workspace-form');
    }
}
