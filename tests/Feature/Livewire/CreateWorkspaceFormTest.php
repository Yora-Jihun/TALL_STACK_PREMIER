<?php

use App\Enums\WorkspaceRole;
use App\Livewire\Workspaces\CreateWorkspaceForm;
use App\Models\User;
use App\Models\Workspace;
use Livewire\Livewire;

it('creates a workspace and redirects to it', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CreateWorkspaceForm::class)
        ->set('name', 'Acme Studio')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('workspaces.show', 'acme-studio'));

    $workspace = Workspace::where('slug', 'acme-studio')->first();

    expect($workspace)->not->toBeNull()
        ->and($workspace->roleOf($user))->toBe(WorkspaceRole::Owner);
});

it('rejects invalid names', function (string $name, string $rule) {
    Livewire::actingAs(User::factory()->create())
        ->test(CreateWorkspaceForm::class)
        ->set('name', $name)
        ->call('save')
        ->assertHasErrors(['name' => $rule]);

    expect(Workspace::count())->toBe(0);
})->with([
    'empty' => ['', 'required'],
    'too short' => ['ab', 'min'],
    'too long' => [str_repeat('a', 51), 'max'],
]);

it('shows the form on the dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertSeeLivewire(CreateWorkspaceForm::class);
});
