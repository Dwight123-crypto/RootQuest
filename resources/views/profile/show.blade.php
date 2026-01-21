<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Profile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- User Info -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-200 mb-4">User Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-400">Username</dt>
                                <dd class="text-gray-200 font-medium">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-400">Email</dt>
                                <dd class="text-gray-200">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-400">Team</dt>
                                <dd class="text-gray-200">{{ $user->team?->name ?? 'No team' }}</dd>
                            </div>
                            @if($user->is_admin)
                                <div>
                                    <span class="inline-block bg-yellow-600 text-white text-sm px-2 py-1 rounded">Admin</span>
                                </div>
                            @endif
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-200 mb-4">Statistics</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-400">Total Score</dt>
                                <dd class="text-2xl font-bold text-green-400">{{ $user->score }} points</dd>
                            </div>
                            @if($rank)
                                <div>
                                    <dt class="text-sm text-gray-400">Rank</dt>
                                    <dd class="text-xl font-semibold text-indigo-400">#{{ $rank }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm text-gray-400">Challenges Solved</dt>
                                <dd class="text-gray-200">{{ $user->solvedChallenges->count() }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Solved Challenges -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-200 mb-4">Solved Challenges</h3>

                @if($user->solvedChallenges->isEmpty())
                    <p class="text-gray-500 italic">No challenges solved yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Challenge</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Category</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-400 uppercase">Points</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-400 uppercase">Solved At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($user->solvedChallenges as $challenge)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-200">{{ $challenge->title }}</td>
                                        <td class="px-4 py-3 text-gray-400">{{ $challenge->category->name }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="bg-indigo-600 text-white text-sm px-2 py-1 rounded">{{ $challenge->points }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-400 text-sm">
                                            {{ $challenge->pivot->solved_at->format('M d, Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
