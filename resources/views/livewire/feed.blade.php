<div class="space-y-10">
    <!-- Filters & Search Bar -->
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
            <!-- Search field -->
            <div class="relative flex-1 max-w-lg">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search by topic, words or vocabulary..." 
                       class="w-full pl-4 pr-10 py-3 border border-cohere-border-light focus:border-cohere-violet focus:ring-1 focus:ring-cohere-violet rounded-cohere-sm text-sm font-body bg-white text-cohere-primary placeholder-cohere-muted transition">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-cohere-slate hover:text-cohere-primary">
                        ✕
                    </button>
                @endif
            </div>

            <!-- Sorting Dropdown -->
            <div class="flex items-center gap-2">
                <span class="text-xs font-mono uppercase text-cohere-slate tracking-wider">{{ __('Sort by:') }}</span>
                <select wire:model.live="sortBy" class="border border-cohere-border-light focus:border-cohere-primary focus:ring-0 rounded-cohere-xs text-xs font-mono uppercase tracking-wider text-cohere-primary bg-white py-2 pl-3 pr-8">
                    <option value="new">{{ __('Newest') }}</option>
                    <option value="popular">{{ __('Most Helpful') }}</option>
                    <option value="discussed">{{ __('Most Discussed') }}</option>
                </select>
            </div>
        </div>

        <!-- Filter Chips (Categories & Levels) -->
        <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-cohere-hairline">
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-xs font-mono uppercase text-cohere-slate tracking-wider mr-2">{{ __('Category:') }}</span>
                <button wire:click="$set('category', '')" 
                        class="px-4 py-1.5 rounded-cohere-pill text-xs font-mono uppercase tracking-wider transition {{ !$category ? 'bg-cohere-primary text-white' : 'border border-cohere-hairline text-cohere-primary hover:border-cohere-primary' }}">
                    {{ __('All') }}
                </button>
                @foreach($categories as $cat)
                    <button wire:click="setCategory('{{ $cat->slug }}')" 
                            class="px-4 py-1.5 rounded-cohere-pill text-xs font-mono uppercase tracking-wider transition {{ $category === $cat->slug ? 'bg-cohere-primary text-white' : 'border border-cohere-hairline text-cohere-primary hover:border-cohere-primary' }}">
                        {{ $cat->title }}
                    </button>
                @endforeach
            </div>

            <!-- Levels -->
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-xs font-mono uppercase text-cohere-slate tracking-wider mr-2">{{ __('English Level:') }}</span>
                @foreach(['A2', 'B1', 'B2'] as $lvl)
                    <button wire:click="setLevel('{{ $lvl }}')" 
                            class="px-3 py-1.5 rounded-cohere-xs text-xs font-mono uppercase tracking-wider transition {{ $level === $lvl ? 'bg-cohere-coral text-white border border-transparent' : 'border border-cohere-hairline text-cohere-primary hover:border-cohere-coral hover:text-cohere-coral' }}">
                        {{ $lvl }}
                    </button>
                @endforeach

                @if($category || $level || $search)
                    <button wire:click="resetFilters" class="text-xs font-mono uppercase tracking-wider text-cohere-blue hover:underline pl-2">
                        {{ __('Reset Filters') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    @if($posts->isEmpty())
        <div class="border border-cohere-hairline p-12 text-center rounded-cohere-md bg-cohere-stone/20">
            <p class="text-cohere-muted font-body text-base">{{ __('No posts found matching the selected filters.') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
                <a href="{{ route('posts.show', $post->slug) }}" class="group bg-white border border-cohere-card-border hover:border-cohere-slate/40 transition-all rounded-cohere-sm overflow-hidden flex flex-col justify-between h-[450px]">
                    <div class="space-y-4">
                        <div class="relative w-full h-48 bg-cohere-stone overflow-hidden border-b border-cohere-card-border">
                            @if(in_array($post->media_type, ['youtube', 'vimeo', 'video', 'vk', 'rutube']) || str_contains($post->media_url, '.mp4') || str_contains($post->media_url, '.webm') || str_contains($post->media_url, 'vk.com') || str_contains($post->media_url, 'vkvideo.ru') || str_contains($post->media_url, 'rutube.ru'))
                                <!-- Video Placeholder Cover -->
                                <div class="absolute inset-0 bg-cohere-deep-green flex items-center justify-center text-white text-4xl group-hover:scale-105 transition duration-500">
                                    🎥
                                </div>
                            @else
                                <img src="{{ $post->media_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex gap-2">
                                <span class="bg-cohere-primary text-white text-[9px] font-mono uppercase tracking-wider px-2 py-0.5 rounded-cohere-xs">
                                    {{ $post->category->title }}
                                </span>
                                <span class="bg-cohere-coral text-white text-[9px] font-mono uppercase tracking-wider px-2 py-0.5 rounded-cohere-xs">
                                    {{ $post->level }}
                                </span>
                            </div>
                        </div>

                        <!-- Content Details -->
                        <div class="px-6 space-y-2">
                            <h4 class="text-lg font-display font-medium text-cohere-primary leading-snug tracking-tight group-hover:text-cohere-blue transition-colors">
                                {{ $post->title }}
                            </h4>
                            <p class="text-xs text-cohere-body-muted font-body leading-relaxed line-clamp-3">
                                {{ $post->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Details -->
                    <div class="px-6 pb-6 pt-4 border-t border-cohere-hairline flex items-center justify-between text-xs font-mono text-cohere-slate">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1">💬 {{ $post->comments_count }}</span>
                            <span class="flex items-center gap-1">👍 {{ $post->reactions_count }}</span>
                        </div>
                        <span class="text-cohere-blue group-hover:underline font-mono uppercase tracking-wider text-[11px]">{{ __('Learn →') }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-6 border-t border-cohere-hairline font-mono">
            {{ $posts->links() }}
        </div>
    @endif
</div>
