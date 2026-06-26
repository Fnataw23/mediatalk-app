<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MediaTalk — Learn English Through Media & Culture</title>

        <!-- Google Fonts: Space Grotesk & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-body antialiased bg-white text-cohere-primary">

        @include('layouts.navigation')

        <!-- MAIN HOMEPAGE WRAPPER -->
        <div class="space-y-24 pb-24">

            <!-- 1. HERO BANNER -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 space-y-12">
                <div class="text-center max-w-4xl mx-auto space-y-6">
                    <h1 class="font-display font-medium text-6xl md:text-8xl tracking-tighter leading-none text-cohere-primary">
                        Watch<span class="text-cohere-slate">.</span> Discuss<span class="text-cohere-coral">.</span> Learn<span class="text-cohere-blue">.</span>
                    </h1>
                    <p class="text-base md:text-xl font-body text-cohere-body-muted leading-relaxed max-w-2xl mx-auto">
                        An interactive educational environment to master English through modern cinema, chart-topping music lyrics, internet memes, and weekly controversial debates.
                    </p>
                    <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                        <a href="{{ route('posts.feed') }}" class="px-8 py-3 bg-cohere-primary text-white text-xs font-mono uppercase tracking-widest rounded-cohere-pill hover:bg-cohere-black transition shadow-sm">
                            Explore Lessons
                        </a>
                        <a href="{{ route('posts.debate') }}" class="px-6 py-3 border border-cohere-hairline hover:border-cohere-primary text-cohere-primary text-xs font-mono uppercase tracking-widest rounded-cohere-pill transition">
                            Join Weekly Debate
                        </a>
                    </div>
                </div>

                <!-- Two-Card Visual Composition -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
                    <div class="md:col-span-2 bg-cohere-stone border border-cohere-card-border p-8 rounded-cohere-lg flex flex-col justify-between min-h-[300px] overflow-hidden relative group">
                        <div class="space-y-2 relative z-10 max-w-md">
                            <span class="text-[10px] font-mono uppercase tracking-widest text-cohere-coral">FEATURED DEMO</span>
                            <h3 class="text-3xl font-display font-medium text-cohere-primary tracking-tight leading-tight">Interactive task runner with live XP gains</h3>
                            <p class="text-xs text-cohere-muted font-body leading-relaxed">
                                Complete spelling, multiple-choice, and word-matching card games under each video to earn experience points, climb tiers, and unlock permanent trophies.
                            </p>
                        </div>
                        <div class="pt-4 z-10">
                            <a href="{{ route('posts.feed') }}" class="text-xs font-mono uppercase tracking-wider text-cohere-blue group-hover:underline">
                                Try Task Console →
                            </a>
                        </div>
                        <!-- Abstract shapes in card background -->
                        <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-cohere-deep-green/5 rounded-full blur-xl transition group-hover:scale-110"></div>
                    </div>

                    <div class="bg-cohere-stone border border-cohere-card-border rounded-cohere-lg overflow-hidden relative group aspect-square md:aspect-auto">
                        <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba" alt="Cinema" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent p-6 flex flex-col justify-end">
                            <span class="text-[9px] font-mono uppercase tracking-wider text-cohere-coral-soft">Cultural context</span>
                            <h4 class="text-lg font-display text-white tracking-tight">Real-World Slangs</h4>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. TRUST MARK LOGO STRIP -->
            <section class="border-y border-cohere-hairline py-8 bg-cohere-stone/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
                    <p class="text-[10px] font-mono uppercase tracking-widest text-cohere-slate">SUPPORTED STUDY SYLLABI</p>
                    <div class="flex flex-wrap items-center justify-center gap-12 md:gap-24 opacity-40 grayscale select-none pt-2">
                        <span class="text-sm font-display tracking-widest uppercase font-semibold">CAMBRIDGE</span>
                        <span class="text-sm font-display tracking-widest uppercase font-semibold">OXFORD</span>
                        <span class="text-sm font-display tracking-widest uppercase font-semibold">TOEFL iBT</span>
                        <span class="text-sm font-display tracking-widest uppercase font-semibold">BRITISH COUNCIL</span>
                    </div>
                </div>
            </section>

            <!-- 3. WEEKLY DEBATE SPOTLIGHT BAND -->
            @if($activeDebate)
                <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-cohere-deep-green text-white p-8 md:p-12 rounded-cohere-lg flex flex-col lg:flex-row gap-8 items-center justify-between relative overflow-hidden">
                        
                        <div class="space-y-4 max-w-xl relative z-10">
                            <span class="px-3 py-1 bg-cohere-coral text-white text-[9px] font-mono uppercase tracking-widest rounded-cohere-xs">
                                WEEKLY DEBATE HOTSPOT
                            </span>
                            <h3 class="text-3xl md:text-4xl font-display font-medium text-white tracking-tight leading-tight">
                                {{ $activeDebate->title }}
                            </h3>
                            <p class="text-xs md:text-sm text-gray-300 font-body leading-relaxed leading-relaxed">
                                {{ Str::limit($activeDebate->description, 200) }}
                            </p>
                        </div>

                        <div class="relative z-10 shrink-0">
                            <a href="{{ route('posts.debate') }}" class="px-8 py-3 bg-[#ffffff] text-[#003c33] hover:bg-[#00f5d4] hover:text-[#08080a] text-xs font-mono uppercase tracking-widest rounded-cohere-pill transition shadow-sm inline-block font-semibold">
                                Vote & Discuss (+10 XP)
                            </a>
                        </div>
                        
                        <!-- Graphic elements -->
                        <div class="absolute -right-16 -top-16 w-48 h-48 bg-cohere-coral/20 rounded-full blur-xl pointer-events-none"></div>
                    </div>
                </section>
            @endif

            <!-- 4. CATEGORIES TILE GRID -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="text-center space-y-2">
                    <span class="text-xs font-mono tracking-widest text-cohere-blue uppercase font-semibold">Study Syllabus</span>
                    <h3 class="text-3xl font-display text-cohere-primary tracking-tight font-medium">Curated Categories</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <a href="{{ route('posts.feed') }}?category=movie-discussions" class="bg-white border border-cohere-card-border p-8 rounded-cohere-md hover:border-cohere-slate transition-all group flex flex-col justify-between h-64">
                        <div class="text-4xl">🎬</div>
                        <div class="space-y-2">
                            <h4 class="text-xl font-display font-medium text-cohere-primary group-hover:text-cohere-blue transition-colors">Movie Discussions</h4>
                            <p class="text-xs text-cohere-muted font-body leading-relaxed">
                                Study real conversational grammar, contractions, and accents from iconic cinematic scenes.
                            </p>
                        </div>
                    </a>

                    <a href="{{ route('posts.feed') }}?category=music-talks" class="bg-white border border-cohere-card-border p-8 rounded-cohere-md hover:border-cohere-slate transition-all group flex flex-col justify-between h-64">
                        <div class="text-4xl">🎵</div>
                        <div class="space-y-2">
                            <h4 class="text-xl font-display font-medium text-cohere-primary group-hover:text-cohere-blue transition-colors">Music Talks</h4>
                            <p class="text-xs text-cohere-muted font-body leading-relaxed">
                                Decipher lyrical meaning, poetic metaphors, and expressive vocabularies from top tracks.
                            </p>
                        </div>
                    </a>

                    <a href="{{ route('posts.feed') }}?category=meme-english" class="bg-white border border-cohere-card-border p-8 rounded-cohere-md hover:border-cohere-slate transition-all group flex flex-col justify-between h-64">
                        <div class="text-4xl">📈</div>
                        <div class="space-y-2">
                            <h4 class="text-xl font-display font-medium text-cohere-primary group-hover:text-cohere-blue transition-colors">Meme English</h4>
                            <p class="text-xs text-cohere-muted font-body leading-relaxed">
                                Dive into modern internet slangs, abbreviations, and cultural TikTok/Reels expressions.
                            </p>
                        </div>
                    </a>

                </div>
            </section>

            <!-- 5. LATEST LESSONS SECTION -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="flex items-end justify-between border-b border-cohere-hairline pb-4">
                    <div class="space-y-2">
                        <span class="text-xs font-mono tracking-widest text-cohere-coral uppercase font-semibold">Newly Published</span>
                        <h3 class="text-3xl font-display text-cohere-primary tracking-tight font-medium">Latest Materials</h3>
                    </div>
                    <a href="{{ route('posts.feed') }}" class="text-xs font-mono uppercase tracking-wider text-cohere-blue hover:underline">
                        View All Lessons →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($latestPosts as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="group bg-white border border-cohere-card-border hover:border-cohere-slate/40 transition-all rounded-cohere-sm overflow-hidden flex flex-col justify-between h-[420px]">
                            <div class="space-y-4">
                                <div class="relative w-full h-44 bg-cohere-stone overflow-hidden border-b border-cohere-card-border">
                                    @if($post->media_type === 'youtube' || $post->media_type === 'vimeo' || $post->media_type === 'video' || str_contains($post->media_url, '.mp4') || str_contains($post->media_url, '.webm'))
                                        <div class="absolute inset-0 bg-cohere-deep-green flex items-center justify-center text-white text-4xl group-hover:scale-105 transition duration-500">
                                            🎥
                                        </div>
                                    @else
                                        <img src="{{ $post->media_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @endif
                                    
                                    <div class="absolute top-3 left-3 flex gap-2">
                                        <span class="bg-cohere-primary text-white text-[9px] font-mono uppercase tracking-wider px-2 py-0.5 rounded-cohere-xs">
                                            {{ $post->category->title }}
                                        </span>
                                    </div>
                                </div>
                                <div class="px-6 space-y-2">
                                    <span class="text-[10px] font-mono uppercase tracking-wider text-cohere-coral font-medium">{{ $post->level }} Difficulty</span>
                                    <h4 class="text-lg font-display font-medium text-cohere-primary leading-snug group-hover:text-cohere-blue transition-colors">
                                        {{ $post->title }}
                                    </h4>
                                    <p class="text-xs text-cohere-muted font-body leading-relaxed line-clamp-3">
                                        {{ $post->description }}
                                    </p>
                                </div>
                            </div>
                            <div class="px-6 pb-6 pt-4 border-t border-cohere-hairline flex items-center justify-between text-xs font-mono text-cohere-slate">
                                <span>💬 {{ $post->comments_count }} comments</span>
                                <span class="text-cohere-blue group-hover:underline uppercase tracking-wider text-[11px]">Learn →</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-cohere-muted font-body italic col-span-3">No posts published yet.</p>
                    @endforelse
                </div>
            </section>

            <!-- 6. POPULAR LESSONS SECTION -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="flex items-end justify-between border-b border-cohere-hairline pb-4">
                    <div class="space-y-2">
                        <span class="text-xs font-mono tracking-widest text-cohere-blue uppercase font-semibold">High Engagement</span>
                        <h3 class="text-3xl font-display text-cohere-primary tracking-tight font-medium">Popular Materials</h3>
                    </div>
                    <a href="{{ route('posts.feed') }}?sortBy=popular" class="text-xs font-mono uppercase tracking-wider text-cohere-blue hover:underline">
                        View Popular →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($popularPosts as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="group bg-white border border-cohere-card-border hover:border-cohere-slate/40 transition-all rounded-cohere-sm overflow-hidden flex flex-col justify-between h-[420px]">
                            <div class="space-y-4">
                                <div class="relative w-full h-44 bg-cohere-stone overflow-hidden border-b border-cohere-card-border">
                                    @if($post->media_type === 'youtube' || $post->media_type === 'vimeo' || $post->media_type === 'video' || str_contains($post->media_url, '.mp4') || str_contains($post->media_url, '.webm'))
                                        <div class="absolute inset-0 bg-cohere-dark-navy flex items-center justify-center text-white text-4xl group-hover:scale-105 transition duration-500">
                                            🎥
                                        </div>
                                    @else
                                        <img src="{{ $post->media_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @endif
                                    
                                    <div class="absolute top-3 left-3 flex gap-2">
                                        <span class="bg-cohere-primary text-white text-[9px] font-mono uppercase tracking-wider px-2 py-0.5 rounded-cohere-xs">
                                            {{ $post->category->title }}
                                        </span>
                                    </div>
                                </div>
                                <div class="px-6 space-y-2">
                                    <span class="text-[10px] font-mono uppercase tracking-wider text-cohere-blue font-medium">{{ $post->level }} Difficulty</span>
                                    <h4 class="text-lg font-display font-medium text-cohere-primary leading-snug group-hover:text-cohere-blue transition-colors">
                                        {{ $post->title }}
                                    </h4>
                                    <p class="text-xs text-cohere-muted font-body leading-relaxed line-clamp-3">
                                        {{ $post->description }}
                                    </p>
                                </div>
                            </div>
                            <div class="px-6 pb-6 pt-4 border-t border-cohere-hairline flex items-center justify-between text-xs font-mono text-cohere-slate">
                                <span>👍 {{ $post->reactions_count }} likes</span>
                                <span class="text-cohere-blue group-hover:underline uppercase tracking-wider text-[11px]">Learn →</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-cohere-muted font-body italic col-span-3">No posts liked yet.</p>
                    @endforelse
                </div>
            </section>

            <!-- 7. FOOTER NEWSLETTER & BRAND STATEMENT -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                <div class="bg-[#121217] text-white p-8 md:p-12 rounded-cohere-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-8 border border-white/10">
                    <div class="space-y-2 max-w-md">
                        <span class="text-xs font-mono uppercase tracking-widest text-cohere-coral-soft">AI moves fast, so does slang.</span>
                        <h4 class="text-2xl font-display font-medium text-white tracking-tight leading-tight">Stay updated with MediaTalk Newsletter</h4>
                        <p class="text-xs text-gray-400 font-body leading-relaxed leading-relaxed">
                            Get fresh cultural slangs, breaking memes, and new debate alerts directly in your inbox once a week.
                        </p>
                    </div>

                    <!-- Single line email form -->
                    <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
                        <input type="email" placeholder="Your email address" class="bg-white/10 border border-white/20 focus:border-white focus:ring-0 text-white placeholder-gray-500 text-xs font-mono uppercase tracking-wider rounded-cohere-pill px-4 py-3 w-full sm:w-64">
                        <button class="px-6 py-3 bg-[#00f5d4] text-[#08080a] hover:bg-[#00e5c9] text-xs font-mono uppercase tracking-widest rounded-cohere-pill transition whitespace-nowrap font-semibold">
                            Subscribe →
                        </button>
                    </div>
                </div>

                <!-- Copyright & Meta links -->
                <div class="flex flex-col sm:flex-row justify-between items-center pt-8 border-t border-cohere-hairline mt-12 text-[10px] font-mono text-cohere-slate uppercase tracking-widest gap-4">
                    <span>© 2026 MEDIATALK EDUCATION INC. ALL RIGHTS RESERVED.</span>
                    <div class="flex gap-4">
                        <a href="#" class="hover:text-cohere-primary transition">TERMS OF USE</a>
                        <a href="#" class="hover:text-cohere-primary transition">PRIVACY POLICY</a>
                        <a href="{{ url('/admin') }}" class="hover:text-cohere-blue transition font-bold">ADMINISTRATIVE ACCESS</a>
                    </div>
                </div>
            </section>

        </div>

        @livewireScripts
    </body>
</html>
