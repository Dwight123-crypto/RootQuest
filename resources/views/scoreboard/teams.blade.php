<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Team Scoreboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($currentTeamRank && $currentTeamScore !== null)
                <div class="bg-indigo-900 border border-indigo-700 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-indigo-200">Your Team Rank</span>
                        <span class="text-2xl font-bold text-white">#{{ $currentTeamRank }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-indigo-200">Your Team Score</span>
                        <span class="text-xl font-semibold text-green-400">{{ $currentTeamScore }} points</span>
                    </div>
                </div>
            @elseif(!auth()->user()->team_id)
                <div class="bg-yellow-900 border border-yellow-700 rounded-lg p-4 mb-6">
                    <p class="text-yellow-200">You are not part of a team. Join a team to appear on the team scoreboard!</p>
                </div>
            @endif

            <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Team</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($rankings as $index => $team)
                            <tr class="{{ auth()->user()->team_id === $team->id ? 'bg-indigo-900/50' : '' }}">
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
                                    <span class="text-gray-200 {{ auth()->user()->team_id === $team->id ? 'font-bold' : '' }}">
                                        {{ $team->name }}
                                        @if(auth()->user()->team_id === $team->id)
                                            <span class="text-indigo-400 text-sm">(your team)</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-green-400 font-semibold">{{ $team->total_score }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    No teams on the scoreboard yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
