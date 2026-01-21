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
        <div class="min-h-screen bg-terminal-bg">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-terminal-surface border-b border-emerald-900/50 shadow-lg shadow-emerald-900/20">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash Messages -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                @if (session('success'))
                    <div class="bg-emerald-900/50 border border-emerald-500/50 text-emerald-400 px-4 py-3 rounded mb-4 font-mono">
                        <span class="text-emerald-500">[+]</span> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-900/50 border border-red-500/50 text-red-400 px-4 py-3 rounded mb-4 font-mono">
                        <span class="text-red-500">[-]</span> {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-terminal-surface border-t border-emerald-900/50 mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-emerald-600 font-mono text-sm">
                    <span class="text-emerald-500">&gt;</span> &copy; {{ date('Y') }} RootQuest Platform <span class="text-emerald-500">|</span> All rights reserved <span class="animate-pulse">_</span>
                </div>
            </footer>
        </div>
    </body>
</html>
