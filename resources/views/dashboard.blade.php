<x-layouts.app title="Dashboard">
    <p>You're logged in as {{ auth()->user()->email }}.</p>
    <livewire:workspaces.create-workspace-form /> 
</x-layouts.app>
