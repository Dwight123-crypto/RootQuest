<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RootQuest') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-terminal-bg">
            <div>
                <a href="/" class="text-3xl font-bold text-emerald-400 hover:text-emerald-300 transition font-mono">
                    <span class="text-emerald-600">&gt;</span> RootQuest<span class="animate-pulse">_</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-terminal-surface border border-emerald-900/50 shadow-lg shadow-emerald-900/20 overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <p class="mt-6 text-emerald-700 text-sm font-mono">
                <span class="text-emerald-600">&gt;</span> Hack the planet_
            </p>
        </div>
    </body>
</html>
