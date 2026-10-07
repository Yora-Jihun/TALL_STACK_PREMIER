<form wire:submit="save" class="space-y-3">
    <div>
        <label for="name" class="block text-sm font-medium">Workspace name</label>
        <input id="name" type="text" wire:model="name"
            class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" wire:loading.attr="disabled"
        class="rounded-md bg-zinc-900 px-4 py-2 font-medium text-white">
        Create workspace
    </button>
</form>
