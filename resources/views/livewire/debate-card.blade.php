<div class="space-y-12">
    @if(!$debate)
        <div class="border border-cohere-hairline p-12 text-center rounded-cohere-md bg-cohere-stone/10">
            <p class="text-sm text-cohere-muted font-body">There are currently no active debates scheduled. Check back soon!</p>
        </div>
    @else
        <!-- Debate Card Container -->
        <div class="bg-cohere-dark-navy text-white p-8 md:p-12 rounded-cohere-lg relative overflow-hidden shadow-sm">
            
            <!-- Abstract background graphic elements -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-cohere-coral/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-cohere-deep-green/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="max-w-3xl mx-auto space-y-8 relative z-10">
                <!-- Header -->
                <div class="space-y-3 text-center">
                    <span class="px-3 py-1 bg-cohere-coral text-white text-[10px] font-mono uppercase tracking-widest rounded-cohere-xs">
                        Weekly Debate Topic
                    </span>
                    <h2 class="text-3xl md:text-5xl font-display font-medium text-white tracking-tight leading-tight pt-2">
                        {{ $debate->title }}
                    </h2>
                </div>

                <!-- Description -->
                <p class="text-sm md:text-base font-body text-gray-300 leading-relaxed text-center max-w-2xl mx-auto">
                    {{ $debate->description }}
                </p>

                <!-- Vote Form or Results -->
                <div class="pt-4 max-w-xl mx-auto space-y-6">
                    @if(!$hasVoted)
                        <!-- A. Clickable buttons -->
                        <div class="grid grid-cols-2 gap-4">
                            <button wire:click="castVote('yes')" class="py-4 border border-cohere-hairline rounded-cohere-pill text-xs font-mono uppercase tracking-widest text-white hover:bg-cohere-deep-green hover:border-transparent transition-all">
                                👍 Yes, I Agree
                            </button>
                            <button wire:click="castVote('no')" class="py-4 border border-cohere-hairline rounded-cohere-pill text-xs font-mono uppercase tracking-widest text-white hover:bg-cohere-coral hover:border-transparent transition-all">
                                👎 No, I Disagree
                            </button>
                        </div>
                    @else
                        <!-- B. Animated Vote Percentages -->
                        @php
                            $stats = $debate->vote_stats;
                        @endphp
                        
                        <div class="space-y-6 bg-white/5 p-6 rounded-cohere-md border border-white/10">
                            <h4 class="text-xs font-mono uppercase tracking-wider text-center text-gray-400">Current Results ({{ $stats['total'] }} votes)</h4>
                            
                            <!-- Stacked Progress Bars -->
                            <div class="space-y-4 font-mono text-xs">
                                <!-- Yes Bar -->
                                <div class="space-y-2">
                                    <div class="flex justify-between uppercase">
                                        <span class="text-cohere-blue font-semibold">Yes, I Agree</span>
                                        <span>{{ $stats['yes_percent'] }}% ({{ $stats['yes_count'] }})</span>
                                    </div>
                                    <div class="w-full bg-white/10 h-3 rounded-full overflow-hidden">
                                        <div class="bg-cohere-deep-green h-full rounded-full transition-all duration-1000" style="width: {{ $stats['yes_percent'] }}%"></div>
                                    </div>
                                </div>

                                <!-- No Bar -->
                                <div class="space-y-2">
                                    <div class="flex justify-between uppercase">
                                        <span class="text-cohere-coral-soft font-semibold">No, I Disagree</span>
                                        <span>{{ $stats['no_percent'] }}% ({{ $stats['no_count'] }})</span>
                                    </div>
                                    <div class="w-full bg-white/10 h-3 rounded-full overflow-hidden">
                                        <div class="bg-cohere-coral h-full rounded-full transition-all duration-1000" style="width: {{ $stats['no_percent'] }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <p class="text-center text-[10px] text-gray-400 font-mono uppercase tracking-widest pt-2">
                                You voted: <span class="text-white font-bold">{{ strtoupper($userVote) }}</span>
                            </p>
                        </div>
                    @endif

                    @if($showFeedback)
                        <div class="p-3 text-center rounded-cohere-xs text-xs font-mono uppercase tracking-wider {{ $showFeedback === 'success' ? 'bg-cohere-deep-green/30 text-cohere-blue border border-cohere-blue/10' : 'bg-red-950/20 text-red-300' }}">
                            {{ $feedbackMessage }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Debate Threaded Comments Block -->
        @if($this->matchingPost)
            <div class="pt-8 max-w-4xl mx-auto border-t border-cohere-hairline">
                <livewire:post-comments :post="$this->matchingPost" />
            </div>
        @endif
    @endif
</div>
