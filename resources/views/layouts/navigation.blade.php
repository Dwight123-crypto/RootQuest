<nav x-data="{ open: false }" class="bg-terminal-surface border-b border-emerald-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-400 hover:text-emerald-300 transition font-mono">
                        <span class="text-emerald-600">&gt;</span> RootQuest<span class="animate-pulse">_</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-emerald-400 hover:text-emerald-300">
                        Challenges
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('scoreboard.users')" :active="request()->routeIs('scoreboard.users')" class="text-emerald-400 hover:text-emerald-300">
                            User Scoreboard
                        </x-nav-link>
                        <x-nav-link :href="route('scoreboard.teams')" :active="request()->routeIs('scoreboard.teams')" class="text-emerald-400 hover:text-emerald-300">
                            Team Scoreboard
                        </x-nav-link>

                        @if(auth()->user()->is_admin)
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-amber-400 hover:text-amber-300">
                                Admin Panel
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <span class="text-emerald-600 mr-4 font-mono text-sm">
                        Score: <span class="text-emerald-400 font-bold">{{ auth()->user()->score }}</span>
                    </span>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-emerald-700 text-sm leading-4 font-medium rounded-md text-emerald-400 bg-terminal-bg hover:text-emerald-300 hover:border-emerald-500 focus:outline-none transition ease-in-out duration-150 font-mono">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.show')">
                                Profile
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                Settings
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 px-3 py-2 font-mono text-sm">Log in</a>
                    <a href="{{ route('register') }}" class="ml-4 bg-emerald-600 text-black px-4 py-2 rounded-md hover:bg-emerald-500 font-mono text-sm font-semibold transition">Register</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-emerald-400 hover:text-emerald-300 hover:bg-terminal-bg focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-terminal-bg border-t border-emerald-900/50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Challenges
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('scoreboard.users')" :active="request()->routeIs('scoreboard.users')">
                    User Scoreboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('scoreboard.teams')" :active="request()->routeIs('scoreboard.teams')">
                    Team Scoreboard
                </x-responsive-nav-link>

                @if(auth()->user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        Admin Panel
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-emerald-900/50">
                <div class="px-4">
                    <div class="font-medium text-base text-emerald-400 font-mono">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-emerald-600">{{ Auth::user()->email }}</div>
                    <div class="font-medium text-sm text-emerald-500 font-mono">Score: {{ Auth::user()->score }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.show')">
                        Profile
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Settings
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-emerald-900/50">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        Log in
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        Register
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
