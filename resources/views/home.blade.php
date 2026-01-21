<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-emerald-400 leading-tight font-mono">
            <span class="text-emerald-600">&gt;</span> Challenges
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @guest
                <div class="bg-terminal-surface border border-emerald-900/50 rounded-lg p-6 mb-6 text-center">
                    <h3 class="text-lg font-semibold text-emerald-400 mb-2 font-mono">Welcome to RootQuest!</h3>
                    <p class="text-emerald-600 mb-4 font-mono text-sm">Please login or register to attempt challenges and track your progress.</p>
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="inline-block bg-emerald-600 text-black px-4 py-2 rounded-md hover:bg-emerald-500 font-mono font-semibold transition">Login</a>
                        <a href="{{ route('register') }}" class="inline-block bg-terminal-bg border border-emerald-600 text-emerald-400 px-4 py-2 rounded-md hover:bg-emerald-900/30 font-mono transition">Register</a>
                    </div>
                </div>
            @endguest

            @forelse($categories as $category)
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-emerald-400 mb-4 border-b border-emerald-900/50 pb-2 font-mono">
                        <span class="text-emerald-600">#</span> {{ $category->name }}
                        <span class="text-sm font-normal text-emerald-700">({{ $category->challenges->count() }} challenges)</span>
                    </h3>

                    @if($category->challenges->isEmpty())
                        <p class="text-emerald-700 italic font-mono text-sm">No challenges in this category yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($category->challenges as $challenge)
                                <div class="bg-terminal-surface border border-emerald-900/50 rounded-lg p-4 hover:border-emerald-500/50 hover:shadow-lg hover:shadow-emerald-900/20 transition-all duration-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="text-lg font-semibold text-emerald-400 font-mono">{{ $challenge->title }}</h4>
                                        <span class="bg-emerald-900/50 text-emerald-400 text-sm px-2 py-1 rounded font-mono border border-emerald-700/50">{{ $challenge->points }} pts</span>
                                    </div>
                                    <p class="text-emerald-600 text-sm mb-4 line-clamp-2 font-mono">{{ Str::limit(strip_tags($challenge->description), 100) }}</p>

                                    @auth
                                        @php
                                            $isSolved = $challenge->isSolvedByTeam(auth()->user());
                                        @endphp

                                        @if($isSolved)
                                            <span class="inline-block bg-emerald-600 text-black text-sm px-3 py-1 rounded font-mono font-semibold">
                                                [+] Solved
                                            </span>
                                        @else
                                            <a href="{{ route('challenges.show', $challenge) }}" class="inline-block bg-emerald-600 text-black text-sm px-3 py-1 rounded hover:bg-emerald-500 font-mono font-semibold transition">
                                                Attempt Challenge
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-emerald-700 text-sm font-mono">Login to attempt</span>
                                    @endauth
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-emerald-700 text-lg font-mono">No challenges available yet. Check back soon!</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
