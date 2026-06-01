<div class="flex flex-wrap items-center gap-4 py-4 border-y border-cohere-hairline">
    <span class="text-xs font-mono uppercase text-cohere-slate tracking-wider">{{ __('Rate this material:') }}</span>
    
    <!-- 1. Useful Reaction -->
    <button wire:click="toggleReaction('useful')"
            class="px-4 py-2 border rounded-cohere-pill text-xs font-mono uppercase tracking-wider flex items-center gap-2 transition {{ $userUseful ? 'bg-cohere-primary text-white border-cohere-primary' : 'bg-white border-cohere-border-light text-cohere-primary hover:border-cohere-primary' }}">
        <span>👍 Useful</span>
        <span class="font-semibold text-[10px]">{{ $usefulCount }}</span>
    </button>

    <!-- 2. Funny Reaction -->
    <button wire:click="toggleReaction('funny')"
            class="px-4 py-2 border rounded-cohere-pill text-xs font-mono uppercase tracking-wider flex items-center gap-2 transition {{ $userFunny ? 'bg-cohere-coral text-white border-cohere-coral' : 'bg-white border-cohere-border-light text-cohere-primary hover:border-cohere-coral hover:text-cohere-coral' }}">
        <span>😂 Funny</span>
        <span class="font-semibold text-[10px]">{{ $funnyCount }}</span>
    </button>

    <!-- 3. Interesting Reaction -->
    <button wire:click="toggleReaction('interesting')"
            class="px-4 py-2 border rounded-cohere-pill text-xs font-mono uppercase tracking-wider flex items-center gap-2 transition {{ $userInteresting ? 'bg-cohere-deep-green text-white border-cohere-deep-green' : 'bg-white border-cohere-border-light text-cohere-primary hover:border-cohere-primary' }}">
        <span>💡 Interesting</span>
        <span class="font-semibold text-[10px]">{{ $interestingCount }}</span>
    </button>
</div>
