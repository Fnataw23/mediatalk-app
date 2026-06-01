<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4 font-mono">
            <div class="flex items-center gap-2">
                <a href="{{ route('posts.feed') }}" class="text-xs uppercase tracking-wider text-cohere-blue hover:underline">
                    ← {{ __('Back to Lessons') }}
                </a>
                <span class="text-cohere-slate">/</span>
                <span class="text-xs uppercase text-cohere-slate tracking-widest">{{ $post->category->title }}</span>
            </div>
            
            <span class="px-2.5 py-0.5 bg-cohere-coral text-white text-[10px] uppercase tracking-widest rounded-cohere-xs">
                {{ $post->level }} Difficulty
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            <!-- 1. Title & Media Frame -->
            <div class="space-y-6">
                <h1 class="text-3xl md:text-5xl font-display font-medium text-cohere-primary tracking-tight leading-tight">
                    {{ $post->title }}
                </h1>
                
                <p class="text-base text-cohere-body-muted font-body leading-relaxed max-w-3xl">
                    {{ $post->description }}
                </p>

                <!-- Media Block -->
                <div class="w-full bg-cohere-stone border border-cohere-card-border rounded-cohere-lg overflow-hidden relative shadow-sm aspect-video">
                    @if($post->media_type === 'youtube')
                        @php
                            $embedUrl = str_replace('www.youtube.com', 'www.youtube-nocookie.com', $post->media_url);
                            $embedUrl = $embedUrl . (str_contains($embedUrl, '?') ? '&' : '?') . 'origin=' . urlencode(request()->getSchemeAndHttpHost());
                        @endphp
                        <iframe class="w-full h-full absolute inset-0 border-0" 
                                src="{{ $embedUrl }}" 
                                title="YouTube video player" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen></iframe>
                    @elseif($post->media_type === 'vimeo')
                        <iframe class="w-full h-full absolute inset-0 border-0" 
                                src="{{ $post->media_url }}" 
                                frameborder="0" 
                                allow="autoplay; fullscreen; picture-in-picture" 
                                allowfullscreen></iframe>
                    @elseif(str_contains($post->media_url, 'drive.google.com'))
                        @php
                            // Automatically extract the Google Drive file ID and convert it to the secure preview URL
                            $driveUrl = $post->media_url;
                            if (str_contains($driveUrl, '/view')) {
                                $driveUrl = str_replace('/view', '/preview', $driveUrl);
                            } elseif (!str_contains($driveUrl, '/preview')) {
                                preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $driveUrl, $matches);
                                if (!empty($matches[1])) {
                                    $driveUrl = "https://drive.google.com/file/d/" . $matches[1] . "/preview";
                                }
                            }
                        @endphp
                        <iframe class="w-full h-full absolute inset-0 border-0" 
                                src="{{ $driveUrl }}" 
                                allow="autoplay" 
                                allowfullscreen></iframe>
                    @elseif($post->media_type === 'video' || str_contains($post->media_url, '.mp4') || str_contains($post->media_url, '.webm') || str_contains($post->media_url, 'dropbox.com'))
                        @php
                            // Support Dropbox direct stream urls
                            $videoUrl = $post->media_url;
                            if (str_contains($videoUrl, 'dropbox.com')) {
                                $videoUrl = str_replace(['www.dropbox.com', '?dl=0', '?dl=1'], ['dl.dropboxusercontent.com', '', ''], $videoUrl);
                            }
                        @endphp
                        <video class="w-full h-full absolute inset-0 bg-black" controls>
                            <source src="{{ $videoUrl }}" type="video/mp4">
                            Your browser does not support the HTML5 video tag.
                        </video>
                    @else
                        <img src="{{ $post->media_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover absolute inset-0">
                    @endif
                </div>

                @if($post->media_type === 'youtube')
                    <div class="mt-2 text-right">
                        <a href="{{ str_replace('embed/', 'watch?v=', $post->media_url) }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-1.5 text-xs font-mono uppercase tracking-wider text-cohere-blue hover:underline bg-cohere-blue/5 border border-cohere-blue/10 px-3.5 py-1.5 rounded-cohere-xs hover:bg-cohere-blue/10 transition-all">
                            📺 Watch directly on YouTube (if player is restricted) →
                        </a>
                    </div>
                @endif
            </div>

            <!-- 2. Vocabulary Block (Interactive Alpine Deck) -->
            @if($post->vocabularies->isNotEmpty())
                <div class="space-y-6 pt-6 border-t border-cohere-hairline">
                    <div class="space-y-2">
                        <span class="text-xs font-mono tracking-widest text-cohere-coral uppercase font-semibold">Vocabulary Deck</span>
                        <h3 class="text-2xl font-display text-cohere-primary tracking-tight font-medium">Keywords & Terms</h3>
                        <p class="text-sm text-cohere-muted font-body">Click on any card to reveal its translation and detailed definition.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($post->vocabularies as $vocab)
                            <div x-data="{ open: false }" 
                                 @click="open = !open" 
                                 class="bg-cohere-stone/40 border border-cohere-card-border p-6 rounded-cohere-sm cursor-pointer hover:border-cohere-slate transition-all select-none flex flex-col justify-between min-h-[140px] space-y-4">
                                
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center w-full">
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xl font-display text-cohere-primary font-medium tracking-tight">{{ $vocab->word }}</h4>
                                            
                                            <!-- TTS Speaker Button -->
                                            <button @click.stop="speakWord('{{ addslashes($vocab->word) }}')" 
                                                    title="Listen pronunciation"
                                                    class="text-cohere-slate hover:text-cohere-blue transition-colors flex items-center justify-center p-1.5 rounded-full hover:bg-white/5 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M12 18.75V5.25L7.75 9.5H4.5v5h3.25L12 18.75z" />
                                                </svg>
                                            </button>
                                        </div>
                                        @if($vocab->transcription)
                                            <span class="text-xs font-mono text-cohere-slate">{{ $vocab->transcription }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Context Sentence -->
                                    @if($vocab->example)
                                        <p class="text-xs text-cohere-body-muted font-body italic leading-relaxed pt-1 border-t border-cohere-hairline/50">
                                            "{!! nl2br(e($vocab->example)) !!}"
                                        </p>
                                    @endif
                                </div>

                                <!-- Accordion reveal translation -->
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     class="pt-4 border-t border-cohere-hairline space-y-2">
                                    <div class="text-sm font-body text-cohere-blue font-medium">
                                        🇷🇺 {{ $vocab->translation }}
                                    </div>
                                    @if($vocab->explanation)
                                        <p class="text-xs text-cohere-muted font-body leading-relaxed leading-relaxed">
                                            {{ $vocab->explanation }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 3. Discussion Questions -->
            @if($post->questions->isNotEmpty())
                <div class="space-y-6 pt-6 border-t border-cohere-hairline">
                    <div class="space-y-2">
                        <span class="text-xs font-mono tracking-widest text-cohere-blue uppercase font-semibold">Discuss</span>
                        <h3 class="text-2xl font-display text-cohere-primary tracking-tight font-medium">Discussion Prompts</h3>
                    </div>

                    <div class="bg-cohere-stone border border-cohere-card-border p-8 rounded-cohere-md space-y-4">
                        @foreach($post->questions as $index => $q)
                            <div class="flex gap-4 items-start">
                                <span class="font-mono text-xs text-cohere-slate mt-1">{{ sprintf('%02d', $index + 1) }}.</span>
                                <p class="text-sm md:text-base font-body text-cohere-primary leading-relaxed font-medium">
                                    {{ $q->text }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 4. Interactive Tasks (confetti on solve, secure XP) -->
            @if($post->tasks->isNotEmpty())
                <div class="space-y-6 pt-6 border-t border-cohere-hairline">
                    <div class="space-y-2">
                        <span class="text-xs font-mono tracking-widest text-cohere-coral uppercase font-semibold">Test Yourself</span>
                        <h3 class="text-2xl font-display text-cohere-primary tracking-tight font-medium">Interactive Assignments</h3>
                    </div>

                    <livewire:task-runner :post="$post" />
                </div>
            @endif

            <!-- 5. Reactions Block -->
            <livewire:post-reactions :post="$post" />

            <!-- 6. Comments Board -->
            <div class="pt-6">
                <livewire:post-comments :post="$post" />
            </div>

        </div>
    </div>

    <!-- Web Speech API TTS script -->
    <script>
        function speakWord(word) {
            if ('speechSynthesis' in window) {
                // Cancel any ongoing speech instantly (prevents lag on rapid clicks)
                window.speechSynthesis.cancel();
                
                const utterance = new SpeechSynthesisUtterance(word);
                utterance.lang = 'en-US'; // Premium native US accent
                
                // Prioritize high-quality voices if available
                const voices = window.speechSynthesis.getVoices();
                const preferredVoice = voices.find(voice => 
                    (voice.lang === 'en-US' || voice.lang === 'en-GB') && voice.name.includes('Google')
                ) || voices.find(voice => 
                    voice.lang === 'en-US' || voice.lang === 'en-GB'
                );
                
                if (preferredVoice) {
                    utterance.voice = preferredVoice;
                }
                
                utterance.rate = 0.85; // Slightly slower speed for absolute learning clarity!
                window.speechSynthesis.speak(utterance);
            } else {
                console.error('Speech synthesis not supported in this browser.');
            }
        }

        // Pre-fetch voices list for perfect compatibility in Chrome/Safari/Firefox
        if ('speechSynthesis' in window) {
            window.speechSynthesis.getVoices();
            if (window.speechSynthesis.onvoiceschanged !== undefined) {
                window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
            }
        }
    </script>
</x-app-layout>
