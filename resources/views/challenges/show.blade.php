<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                    {{ $challenge->title }}
                </h2>
                <p class="text-gray-400 text-sm">{{ $challenge->category->name }}</p>
            </div>
            <span class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-lg font-bold">
                {{ $challenge->points }} points
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Challenge Description -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-200 mb-4">Description</h3>
                <div class="prose prose-invert max-w-none text-gray-300">
                    {!! nl2br(e($challenge->description)) !!}
                </div>

                @if($challenge->hasFile())
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <a href="{{ route('challenges.download', $challenge) }}"
                           class="inline-flex items-center text-indigo-400 hover:text-indigo-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Challenge File
                        </a>
                    </div>
                @endif
            </div>

            <!-- Flag Submission -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-200 mb-4">Submit Flag</h3>

                @if($isSolved)
                    <div class="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded">
                        This challenge has been solved by you or your team!
                    </div>
                @else
                    <form action="{{ route('challenges.submit', $challenge) }}" method="POST" class="flex gap-4">
                        @csrf
                        <input type="text" name="flag"
                               placeholder="Enter flag here..."
                               class="flex-1 bg-gray-700 border-gray-600 text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                               required>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                            Submit
                        </button>
                    </form>
                    @error('flag')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <!-- Hints Section -->
            @if($challenge->hints->isNotEmpty())
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-200 mb-4">Hints</h3>

                    <div class="space-y-4">
                        @foreach($challenge->hints as $index => $hint)
                            <div class="border border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium text-gray-300">Hint {{ $index + 1 }}</span>
                                    <span class="text-yellow-400 text-sm">Cost: {{ $hint->cost }} points</span>
                                </div>

                                @if(in_array($hint->id, $unlockedHintIds))
                                    <div class="bg-gray-700 rounded p-3 text-gray-300">
                                        {{ $hint->content }}
                                    </div>
                                @else
                                    <form action="{{ route('challenges.unlock-hint', $challenge) }}" method="POST" class="flex items-center gap-4">
                                        @csrf
                                        <input type="hidden" name="hint_id" value="{{ $hint->id }}">
                                        <p class="text-gray-500 italic flex-1">This hint is locked.</p>
                                        <button type="submit"
                                                class="bg-yellow-600 text-white px-4 py-1 rounded text-sm hover:bg-yellow-700"
                                                onclick="return confirm('Are you sure? This will deduct {{ $hint->cost }} points from your score.')">
                                            Unlock (-{{ $hint->cost }} pts)
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-300">
                    &larr; Back to Challenges
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
