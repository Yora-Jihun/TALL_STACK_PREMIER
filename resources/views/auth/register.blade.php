<x-layouts.guest title="Create account">
    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}"
                required autofocus autocomplete="name"
                class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                required autocomplete="username"
                class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium">Password</label>
            <input id="password" name="password" type="password"
                required autocomplete="new-password"
                class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                required autocomplete="new-password"
                class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2">
        </div>

        <button type="submit" class="w-full rounded-md bg-zinc-900 px-4 py-2 font-medium text-white">
            Create account
        </button>
    </form>

    <p class="mt-6 text-sm text-zinc-600">
        Already registered? <a href="{{ route('login') }}" class="underline">Log in</a>
    </p>
</x-layouts.guest>
