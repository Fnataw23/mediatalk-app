<div class="space-y-8">
    <div class="space-y-2">
        <span class="text-xs font-mono tracking-widest text-cohere-blue uppercase font-semibold">Join the discussion</span>
        <h3 class="text-2xl font-display text-cohere-primary tracking-tight font-medium">Comments</h3>
    </div>

    <!-- 1. Add Comment Form -->
    @auth
        <form wire:submit.prevent="addComment" class="space-y-3">
            <textarea wire:model="commentText" 
                      placeholder="Write your thoughts, observations, or vocabulary questions..." 
                      rows="4" 
                      class="w-full border border-cohere-border-light focus:border-cohere-primary focus:ring-0 rounded-cohere-sm text-sm font-body p-4 bg-white text-cohere-primary transition"></textarea>
            @error('commentText')
                <span class="text-xs text-red-600 font-mono">{{ $message }}</span>
            @enderror
            <div class="flex justify-between items-center">
                <span class="text-xs font-mono text-cohere-slate uppercase tracking-wider">+5 XP for commenting</span>
                <button type="submit" class="px-6 py-2 bg-cohere-primary text-white text-xs font-mono uppercase tracking-wider rounded-cohere-pill hover:bg-cohere-black transition">
                    Post Comment
                </button>
            </div>
        </form>
    @else
        <div class="border border-cohere-hairline p-6 text-center rounded-cohere-sm bg-cohere-stone/10">
            <p class="text-sm text-cohere-muted font-body">
                Please <a href="{{ route('login') }}" class="text-cohere-blue hover:underline font-mono">log in</a> to participate in the discussions.
            </p>
        </div>
    @endauth

    <!-- 2. Comments List -->
    @if($comments->isEmpty())
        <p class="text-sm text-cohere-muted font-body italic py-4">{{ __('No comments yet. Be the first to start the conversation!') }}</p>
    @else
        <div class="space-y-6 pt-4 border-t border-cohere-hairline">
            @foreach($comments as $comment)
                <!-- LEVEL 1: Root Comment -->
                <div class="space-y-4">
                    <div class="flex gap-4 items-start">
                        <!-- Avatar -->
                        @if($comment->user->avatar)
                            <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-cohere-primary flex items-center justify-center text-white text-sm font-display uppercase shrink-0">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                        @endif

                        <!-- Body -->
                        <div class="flex-1 space-y-1">
                            <div class="flex flex-wrap items-baseline gap-2">
                                <span class="text-sm font-display font-medium text-cohere-primary">{{ $comment->user->name }}</span>
                                <span class="text-[9px] font-mono uppercase tracking-wider px-1.5 py-0.5 bg-cohere-stone border border-cohere-hairline text-cohere-slate rounded-cohere-xs">
                                    {{ $comment->user->english_level }}
                                </span>
                                <span class="text-[10px] font-mono text-cohere-slate">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <p class="text-sm text-cohere-ink font-body leading-relaxed whitespace-pre-wrap">{{ $comment->content }}</p>
                            
                            <!-- Comment Actions -->
                            <div class="flex items-center gap-4 pt-2 text-xs font-mono">
                                <button wire:click="toggleLike({{ $comment->id }})" class="flex items-center gap-1 hover:text-cohere-primary transition {{ Auth::user() && $comment->isLikedBy(Auth::user()) ? 'text-cohere-coral font-medium' : 'text-cohere-slate' }}">
                                    ❤️ {{ $comment->likes()->count() }}
                                </button>
                                <button wire:click="toggleReply({{ $comment->id }})" class="text-cohere-slate hover:text-cohere-blue transition">
                                    Reply
                                </button>
                                @if(Auth::user() && ($comment->user_id === Auth::id() || Auth::user()->is_admin))
                                    <button wire:click="deleteComment({{ $comment->id }})" class="text-red-600/70 hover:text-red-600 transition">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Level 1 Inline Reply Editor -->
                    @if($replyingToId === $comment->id)
                        <div class="ml-14 bg-cohere-stone/20 p-4 border border-cohere-card-border rounded-cohere-sm">
                            <form wire:submit.prevent="addReply({{ $comment->id }})" class="space-y-3">
                                <textarea wire:model="replyText" 
                                          placeholder="Write your reply..." 
                                          rows="3" 
                                          class="w-full border border-cohere-border-light focus:border-cohere-primary focus:ring-0 rounded-cohere-xs text-xs font-body p-3 bg-white text-cohere-primary transition"></textarea>
                                @error('replyText')
                                    <span class="text-xs text-red-600 font-mono">{{ $message }}</span>
                                @enderror
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-mono text-cohere-slate uppercase tracking-wider">+3 XP for replying</span>
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="toggleReply({{ $comment->id }})" class="px-4 py-1.5 border border-cohere-hairline text-cohere-primary text-[10px] font-mono uppercase tracking-wider rounded-cohere-pill hover:border-cohere-primary transition">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-4 py-1.5 bg-cohere-primary text-white text-[10px] font-mono uppercase tracking-wider rounded-cohere-pill hover:bg-cohere-black transition">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- LEVEL 2: Replies -->
                    @if($comment->replies->isNotEmpty())
                        <div class="ml-12 pl-4 border-l border-cohere-hairline space-y-4">
                            @foreach($comment->replies as $reply)
                                <div class="space-y-4">
                                    <div class="flex gap-3 items-start">
                                        <!-- Avatar -->
                                        @if($reply->user->avatar)
                                            <img src="{{ asset('storage/' . $reply->user->avatar) }}" alt="" class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-cohere-stone border border-cohere-hairline flex items-center justify-center text-cohere-primary text-xs font-display uppercase shrink-0">
                                                {{ substr($reply->user->name, 0, 1) }}
                                            </div>
                                        @endif

                                        <!-- Body -->
                                        <div class="flex-1 space-y-1">
                                            <div class="flex flex-wrap items-baseline gap-2">
                                                <span class="text-xs font-display font-medium text-cohere-primary">{{ $reply->user->name }}</span>
                                                <span class="text-[8px] font-mono uppercase tracking-wider px-1 py-0.5 bg-cohere-stone border border-cohere-hairline text-cohere-slate rounded-cohere-xs">
                                                    {{ $reply->user->english_level }}
                                                </span>
                                                <span class="text-[9px] font-mono text-cohere-slate">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            
                                            <p class="text-xs text-cohere-ink font-body leading-relaxed whitespace-pre-wrap">{{ $reply->content }}</p>
                                            
                                            <!-- Actions -->
                                            <div class="flex items-center gap-4 pt-1 text-[10px] font-mono">
                                                <button wire:click="toggleLike({{ $reply->id }})" class="flex items-center gap-1 hover:text-cohere-primary transition {{ Auth::user() && $reply->isLikedBy(Auth::user()) ? 'text-cohere-coral font-medium' : 'text-cohere-slate' }}">
                                                    ❤️ {{ $reply->likes()->count() }}
                                                </button>
                                                <button wire:click="toggleReply({{ $reply->id }})" class="text-cohere-slate hover:text-cohere-blue transition">
                                                    Reply
                                                </button>
                                                @if(Auth::user() && ($reply->user_id === Auth::id() || Auth::user()->is_admin))
                                                    <button wire:click="deleteComment({{ $reply->id }})" class="text-red-600/70 hover:text-red-600 transition">
                                                        Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Level 2 Inline Reply Editor -->
                                    @if($replyingToId === $reply->id)
                                        <div class="ml-11 bg-cohere-stone/20 p-4 border border-cohere-card-border rounded-cohere-sm">
                                            <form wire:submit.prevent="addReply({{ $reply->id }})" class="space-y-3">
                                                <textarea wire:model="replyText" 
                                                          placeholder="Write your reply..." 
                                                          rows="3" 
                                                          class="w-full border border-cohere-border-light focus:border-cohere-primary focus:ring-0 rounded-cohere-xs text-xs font-body p-3 bg-white text-cohere-primary transition"></textarea>
                                                @error('replyText')
                                                    <span class="text-xs text-red-600 font-mono">{{ $message }}</span>
                                                @enderror
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[10px] font-mono text-cohere-slate uppercase tracking-wider">+3 XP for replying</span>
                                                    <div class="flex gap-2">
                                                        <button type="button" wire:click="toggleReply({{ $reply->id }})" class="px-4 py-1.5 border border-cohere-hairline text-cohere-primary text-[10px] font-mono uppercase tracking-wider rounded-cohere-pill hover:border-cohere-primary transition">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="px-4 py-1.5 bg-cohere-primary text-white text-[10px] font-mono uppercase tracking-wider rounded-cohere-pill hover:bg-cohere-black transition">
                                                            Reply
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- LEVEL 3: Sub-replies (visual nesting capped, further replies render flat at Level 3) -->
                                    @if($reply->replies->isNotEmpty())
                                        <div class="ml-10 pl-3 border-l border-cohere-hairline space-y-4">
                                            @foreach($reply->replies as $subReply)
                                                <div class="flex gap-3 items-start">
                                                    <!-- Avatar -->
                                                    @if($subReply->user->avatar)
                                                        <img src="{{ asset('storage/' . $subReply->user->avatar) }}" alt="" class="w-7 h-7 rounded-full object-cover">
                                                    @else
                                                        <div class="w-7 h-7 rounded-full bg-cohere-stone border border-cohere-hairline flex items-center justify-center text-cohere-primary text-[10px] font-display uppercase shrink-0">
                                                            {{ substr($subReply->user->name, 0, 1) }}
                                                        </div>
                                                    @endif

                                                    <!-- Body -->
                                                    <div class="flex-1 space-y-1">
                                                        <div class="flex flex-wrap items-baseline gap-2">
                                                            <span class="text-[11px] font-display font-medium text-cohere-primary">{{ $subReply->user->name }}</span>
                                                            <span class="text-[7px] font-mono uppercase tracking-wider px-1 py-0.5 bg-cohere-stone border border-cohere-hairline text-cohere-slate rounded-cohere-xs">
                                                                {{ $subReply->user->english_level }}
                                                            </span>
                                                            <span class="text-[8px] font-mono text-cohere-slate">{{ $subReply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        
                                                        <p class="text-xs text-cohere-ink font-body leading-relaxed whitespace-pre-wrap"><span class="text-cohere-blue font-mono text-[10px] mr-1">@to:{{ $reply->user->name }}</span>{{ $subReply->content }}</p>
                                                        
                                                        <!-- Actions -->
                                                        <div class="flex items-center gap-4 pt-1 text-[9px] font-mono">
                                                            <button wire:click="toggleLike({{ $subReply->id }})" class="flex items-center gap-1 hover:text-cohere-primary transition {{ Auth::user() && $subReply->isLikedBy(Auth::user()) ? 'text-cohere-coral font-medium' : 'text-cohere-slate' }}">
                                                                ❤️ {{ $subReply->likes()->count() }}
                                                            </button>
                                                            @if(Auth::user() && ($subReply->user_id === Auth::id() || Auth::user()->is_admin))
                                                                <button wire:click="deleteComment({{ $subReply->id }})" class="text-red-600/70 hover:text-red-600 transition">
                                                                    Delete
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Pagination Links -->
            <div class="pt-6 font-mono">
                {{ $comments->links() }}
            </div>
        </div>
    @endif
</div>
