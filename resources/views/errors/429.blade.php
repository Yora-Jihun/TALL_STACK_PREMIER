<x-layouts.guest title="Too many attempts">
    <p class="text-zinc-600">
        You've tried too many times in a short period. Please wait a minute, then try again.
    </p>

    <a href="{{ route('login') }}" class="mt-6 inline-block underline">Back to log in</a>
</x-layouts.guest>
