<x-layouts.app :title="$workspace->name">
    <h2 class="mb-4 text-lg font-medium">Projects</h2>

    @forelse ($projects as $project)
        <div class="mb-2 rounded-md border border-zinc-200 bg-white px-4 py-3">
            {{ $project->name }}
        </div>
    @empty
        <p class="text-zinc-600">No projects yet.</p>
    @endforelse
</x-layouts.app>
