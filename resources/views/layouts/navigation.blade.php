<nav x-data="{ open: false }" class="bg-white border-b border-cohere-hairline">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Zone: Logo & Public Links -->
            <div class="flex items-center gap-8">
                <!-- Typographical Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="font-display font-semibold tracking-tight text-xl text-cohere-primary hover:opacity-80 transition">
                        MediaTalk<span class="text-cohere-coral">.</span>
                    </a>
                </div>

                <!-- Central Navigation Links (Desktop) -->
                <div class="hidden sm:flex sm:items-center sm:space-x-6 h-full pt-1">
                    <a href="{{ url('/') }}" class="text-xs font-mono uppercase tracking-wider px-2 py-1 text-cohere-slate hover:text-cohere-primary transition {{ request()->is('/') ? 'text-cohere-primary font-semibold border-b-2 border-cohere-primary h-full flex items-center' : 'h-full flex items-center' }}">
                        {{ __('Home') }}
                    </a>
                    <a href="{{ route('posts.feed') }}" class="text-xs font-mono uppercase tracking-wider px-2 py-1 text-cohere-slate hover:text-cohere-primary transition {{ request()->routeIs('posts.feed') || request()->is('posts/*') ? 'text-cohere-primary font-semibold border-b-2 border-cohere-primary h-full flex items-center' : 'h-full flex items-center' }}">
                        {{ __('Lessons') }}
                    </a>
                    <a href="{{ route('posts.debate') }}" class="text-xs font-mono uppercase tracking-wider px-2 py-1 text-cohere-slate hover:text-cohere-primary transition {{ request()->routeIs('posts.debate') ? 'text-cohere-primary font-semibold border-b-2 border-cohere-primary h-full flex items-center' : 'h-full flex items-center' }}">
                        {{ __('Weekly Debate') }}
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-xs font-mono uppercase tracking-wider px-2 py-1 text-cohere-slate hover:text-cohere-primary transition {{ request()->routeIs('dashboard') ? 'text-cohere-primary font-semibold border-b-2 border-cohere-primary h-full flex items-center' : 'h-full flex items-center' }}">
                            {{ __('Dashboard') }}
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Zone: XP Badge & User Menu or Auth buttons -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                
                @auth
                    <!-- Dynamic XP & Level Badge -->
                    <a href="{{ route('dashboard') }}" class="px-3 py-1 bg-cohere-stone border border-cohere-hairline rounded-cohere-pill flex items-center gap-1.5 hover:border-cohere-primary transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-cohere-coral"></span>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-cohere-primary font-medium">
                            LEVEL {{ Auth::user()->level }} • {{ Auth::user()->xp }} XP
                        </span>
                    </a>

                    <!-- Admin quick shortcut -->
                    @if(Auth::user()->is_admin)
                        <a href="{{ url('/admin') }}" class="text-[10px] font-mono uppercase tracking-wider text-cohere-blue border border-cohere-blue/30 px-3 py-1 rounded-cohere-pill hover:bg-cohere-blue hover:text-white transition">
                            Admin Panel
                        </a>
                    @endif

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-xs font-mono uppercase tracking-wider text-cohere-primary bg-white hover:text-cohere-slate focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="font-mono text-xs uppercase tracking-wide">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="font-mono text-xs uppercase tracking-wide text-red-600"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Guest Authentication Actions -->
                    <a href="{{ route('login') }}" class="text-xs font-mono uppercase tracking-wider text-cohere-primary hover:text-cohere-slate transition mr-2">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-cohere-primary text-white text-xs font-mono uppercase tracking-wider rounded-cohere-pill hover:bg-cohere-black transition shadow-sm">
                        {{ __('Register') }}
                    </a>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-cohere-sm text-cohere-slate hover:text-cohere-primary focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-cohere-hairline">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('posts.feed')" :active="request()->routeIs('posts.feed') || request()->is('posts/*')">
                {{ __('Lessons') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('posts.debate')" :active="request()->routeIs('posts.debate')">
                {{ __('Weekly Debate') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Auth Actions / Stats -->
        <div class="pt-4 pb-1 border-t border-cohere-hairline bg-cohere-stone/10">
            @auth
                <div class="px-4 py-2 space-y-2">
                    <div class="font-display font-medium text-sm text-cohere-primary">{{ Auth::user()->name }}</div>
                    <div class="font-body text-xs text-cohere-muted">{{ Auth::user()->email }}</div>
                    
                    <div class="inline-block mt-2 px-3 py-1 bg-cohere-primary text-white text-[9px] font-mono uppercase tracking-wider rounded-cohere-pill">
                        LEVEL {{ Auth::user()->level }} • {{ Auth::user()->xp }} XP
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    @if(Auth::user()->is_admin)
                        <x-responsive-nav-link :href="url('/admin')">
                            {{ __('Admin Panel') }}
                        </x-responsive-nav-link>
                    @endif
                    
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                class="text-red-600">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-3 flex gap-2">
                    <a href="{{ route('login') }}" class="flex-1 text-center py-2 border border-cohere-primary text-cohere-primary text-xs font-mono uppercase tracking-wider rounded-cohere-pill">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="flex-1 text-center py-2 bg-cohere-primary text-white text-xs font-mono uppercase tracking-wider rounded-cohere-pill">
                        {{ __('Register') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
