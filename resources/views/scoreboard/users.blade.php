<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            User Scoreboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($currentUserRank)
                <div class="bg-indigo-900 border border-indigo-700 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-indigo-200">Your Rank</span>
                        <span class="text-2xl font-bold text-white">#{{ $currentUserRank }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-indigo-200">Your Score</span>
                        <span class="text-xl font-semibold text-green-400">{{ $currentUserScore }} points</span>
                    </div>
                </div>
            @endif

            <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($rankings as $index => $user)
                            <tr class="{{ auth()->id() === $user->id ? 'bg-indigo-900/50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($index < 3)
                                        <span class="text-2xl">
                                            @if($index === 0) @endif
                                            @if($index === 1) @endif
                                            @if($index === 2) @endif
                                        </span>
                                    @else
                                        <span class="text-gray-400">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-200 {{ auth()->id() === $user->id ? 'font-bold' : '' }}">
                                        {{ $user->name }}
                                        @if(auth()->id() === $user->id)
                                            <span class="text-indigo-400 text-sm">(you)</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-green-400 font-semibold">{{ $user->score }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    No users on the scoreboard yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
