<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-4xl text-cohere-primary leading-tight tracking-tight">
            {{ __('Command Center') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            <!-- 1. Profile Hero Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Profile Card -->
                <div class="lg:col-span-2 bg-white border border-cohere-hair-line p-8 rounded-cohere-md flex flex-col md:flex-row gap-8 items-center md:items-start">
                    
                    <!-- Avatar Area -->
                    <div class="flex flex-col items-center gap-4">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-32 h-32 rounded-full object-cover border border-cohere-hairline shadow-sm">
                        @else
                            <div class="w-32 h-32 rounded-full bg-cohere-primary flex items-center justify-center text-white text-4xl font-display uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif

                        <!-- Avatar Upload Form -->
                        <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center gap-2">
                            @csrf
                            <label class="cursor-pointer text-xs text-cohere-blue hover:underline font-mono tracking-wide uppercase">
                                {{ __('Change Photo') }}
                                <input type="file" name="avatar" class="hidden" onchange="this.form.submit()">
                            </label>
                            @error('avatar')
                                <span class="text-xs text-red-600 font-mono">{{ $message }}</span>
                            @enderror
                        </form>
                    </div>

                    <!-- User Information & XP -->
                    <div class="flex-1 w-full text-center md:text-left space-y-6">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                <h1 class="text-3xl font-display text-cohere-primary tracking-tight font-medium">{{ Auth::user()->name }}</h1>
                                <span class="px-3 py-1 border border-cohere-primary rounded-cohere-pill text-xs font-mono tracking-wider uppercase text-cohere-primary">
                                    {{ Auth::user()->english_level }}
                                </span>
                            </div>
                            <p class="text-sm text-cohere-muted font-body">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Gamification Area -->
                        <div class="space-y-3 bg-cohere-stone/40 p-6 rounded-cohere-sm border border-cohere-card-border">
                            <div class="flex justify-between items-end font-mono">
                                <span class="text-xs uppercase tracking-widest text-cohere-slate">LEVEL {{ Auth::user()->level }}</span>
                                <span class="text-xs text-cohere-primary font-medium">{{ Auth::user()->xp }} / {{ Auth::user()->xp_for_next_level }} XP</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-cohere-hairline h-2 rounded-full overflow-hidden">
                                <div class="bg-cohere-deep-green h-full rounded-full transition-all duration-500" style="width: {{ Auth::user()->xp_progress }}%;"></div>
                            </div>
                            <div class="flex justify-between text-[11px] font-mono text-cohere-muted">
                                <span>{{ Auth::user()->xp_for_current_level }} XP</span>
                                <span>{{ Auth::user()->xp_for_next_level - Auth::user()->xp }} XP to Level up</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Profile Fast Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-4 w-full h-full justify-between">
                    
                    <div class="bg-cohere-stone border border-cohere-card-border p-6 rounded-cohere-sm flex flex-col justify-between min-h-[100px]">
                        <span class="text-xs font-mono uppercase tracking-wider text-cohere-slate">{{ __('Comments Written') }}</span>
                        <span class="text-4xl font-display font-medium text-cohere-primary mt-2">{{ Auth::user()->comments()->count() }}</span>
                    </div>

                    <div class="bg-cohere-stone border border-cohere-card-border p-6 rounded-cohere-sm flex flex-col justify-between min-h-[100px]">
                        <span class="text-xs font-mono uppercase tracking-wider text-cohere-slate">{{ __('Tasks Solved') }}</span>
                        <span class="text-4xl font-display font-medium text-cohere-primary mt-2">{{ Auth::user()->completedTasks()->count() }}</span>
                    </div>

                    <div class="bg-cohere-stone border border-cohere-card-border p-6 rounded-cohere-sm flex flex-col justify-between min-h-[100px]">
                        <span class="text-xs font-mono uppercase tracking-wider text-cohere-slate">{{ __('Debates Voted') }}</span>
                        <span class="text-4xl font-display font-medium text-cohere-primary mt-2">{{ Auth::user()->debateVotes()->count() }}</span>
                    </div>

                </div>

            </div>

            <hr class="border-cohere-hairline">

            <!-- 3. Achievements Section -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <span class="text-xs font-mono tracking-widest text-cohere-coral uppercase font-semibold">Trophy Case</span>
                    <h3 class="text-3xl font-display text-cohere-primary tracking-tight font-medium">Achievements</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $allAchievements = \App\Models\Achievement::all();
                        $unlockedIds = Auth::user()->achievements->pluck('id')->toArray();
                    @endphp

                    @foreach($allAchievements as $achievement)
                        @php
                            $isUnlocked = in_array($achievement->id, $unlockedIds);
                            $pivotData = $isUnlocked ? Auth::user()->achievements->find($achievement->id)->pivot : null;
                        @endphp

                        @if($isUnlocked)
                            <!-- Unlocked Card -->
                            <div class="bg-white border border-cohere-border-light p-6 rounded-cohere-md relative flex flex-col justify-between h-48 transition hover:border-cohere-slate/40">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-cohere-pale-green flex items-center justify-center text-cohere-deep-green">
                                            🏆
                                        </div>
                                        <h4 class="font-display font-medium text-cohere-primary text-lg">{{ $achievement->title }}</h4>
                                    </div>
                                    <p class="text-sm text-cohere-body-muted font-body leading-relaxed">{{ $achievement->description }}</p>
                                </div>
                                
                                <div class="flex justify-between items-center text-[11px] font-mono text-cohere-slate mt-4 pt-3 border-t border-cohere-hairline">
                                    <span class="text-cohere-deep-green uppercase font-semibold tracking-wider">UNLOCKED</span>
                                    <span>{{ $pivotData ? \Carbon\Carbon::parse($pivotData->created_at)->format('M d, Y') : '' }}</span>
                                </div>
                            </div>
                        @else
                            <!-- Locked Card -->
                            <div class="bg-cohere-stone/40 border border-cohere-card-border p-6 rounded-cohere-md flex flex-col justify-between h-48 opacity-50 select-none">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-cohere-hairline flex items-center justify-center text-cohere-slate">
                                            🔒
                                        </div>
                                        <h4 class="font-display font-medium text-cohere-slate text-lg">{{ $achievement->title }}</h4>
                                    </div>
                                    <p class="text-sm text-cohere-muted font-body leading-relaxed">{{ $achievement->description }}</p>
                                </div>
                                
                                <div class="text-[11px] font-mono text-cohere-slate mt-4 pt-3 border-t border-cohere-hairline uppercase tracking-widest text-right">
                                    LOCKED
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
