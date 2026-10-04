@props(['title'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title }} - {{ config('app.name') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">
        <header class="border-b border-zinc-200 bg-white">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
                <a href="{{ route('dashboard') }}" class="font-semibold">{{ config('app.name') }}</a>

                <div class="flex items-center gap-4 text-sm">
                    <span>{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="underline">Log out</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-6 py-8">
            <h1 class="mb-6 text-2xl font-semibold">{{ $title }}</h1>

            {{ $slot }}
        </main>
    </body>
</html>
