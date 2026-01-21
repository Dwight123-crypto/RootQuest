<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <div class="text-gray-400 text-sm">Total Users</div>
                    <div class="text-3xl font-bold text-indigo-400">{{ $stats['users'] }}</div>
                </div>
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <div class="text-gray-400 text-sm">Total Teams</div>
                    <div class="text-3xl font-bold text-green-400">{{ $stats['teams'] }}</div>
                </div>
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <div class="text-gray-400 text-sm">Categories</div>
                    <div class="text-3xl font-bold text-yellow-400">{{ $stats['categories'] }}</div>
                </div>
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <div class="text-gray-400 text-sm">Challenges</div>
                    <div class="text-3xl font-bold text-purple-400">{{ $stats['challenges'] }}</div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-200 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-4 text-center transition-colors">
                        <div class="text-yellow-400 text-2xl mb-2">+</div>
                        <div class="text-gray-300 text-sm">Categories</div>
                    </a>
                    <a href="{{ route('admin.challenges.index') }}" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-4 text-center transition-colors">
                        <div class="text-purple-400 text-2xl mb-2">+</div>
                        <div class="text-gray-300 text-sm">Challenges</div>
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-4 text-center transition-colors">
                        <div class="text-green-400 text-2xl mb-2">+</div>
                        <div class="text-gray-300 text-sm">Teams</div>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-4 text-center transition-colors">
                        <div class="text-indigo-400 text-2xl mb-2">@</div>
                        <div class="text-gray-300 text-sm">Users</div>
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-4 text-center transition-colors">
                        <div class="text-red-400 text-2xl mb-2">!</div>
                        <div class="text-gray-300 text-sm">Logs</div>
                    </a>
                    <a href="{{ route('home') }}" class="bg-gray-700 hover:bg-gray-600 rounded-lg p-4 text-center transition-colors">
                        <div class="text-gray-400 text-2xl mb-2">&larr;</div>
                        <div class="text-gray-300 text-sm">View Site</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
