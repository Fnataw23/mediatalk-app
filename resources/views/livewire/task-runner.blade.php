<div class="bg-cohere-stone/30 border border-cohere-card-border p-8 rounded-cohere-md space-y-6" x-data="{ confettiTriggered: false }" x-effect="if ($wire.showConfetti && !confettiTriggered) { triggerConfetti(); confettiTriggered = true; }">
    
    <!-- JavaScript Confetti Integration -->
    <script>
        function triggerConfetti() {
            // Standard decorative confetti effects via simple CSS/JS or Alpine
            if (window.confetti) {
                window.confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            } else {
                // Fallback custom simple dots if script not loaded
                const duration = 2 * 1000;
                const end = Date.now() + duration;
                
                const frame = () => {
                    const el = document.createElement('div');
                    el.innerText = '🎉';
                    el.style.position = 'fixed';
                    el.style.left = Math.random() * 100 + 'vw';
                    el.style.top = '-20px';
                    el.style.fontSize = '24px';
                    el.style.zIndex = '9999';
                    el.style.transition = 'transform 2s ease, opacity 2s ease';
                    document.body.appendChild(el);
                    
                    setTimeout(() => {
                        el.style.transform = `translateY(100vh) rotate(${Math.random() * 360}deg)`;
                        el.style.opacity = '0';
                    }, 50);
                    
                    setTimeout(() => el.remove(), 2500);
                    if (Date.now() < end) requestAnimationFrame(frame);
                };
                frame();
            }
        }
    </script>

    @if(empty($tasks) || !isset($tasks[$currentTaskIndex]))
        <!-- All Tasks Done -->
        <div class="text-center py-8 space-y-4">
            <div class="text-5xl">🎓</div>
            <h4 class="text-2xl font-display font-medium text-cohere-primary tracking-tight">Post Material Completed!</h4>
            <p class="text-sm text-cohere-muted font-body max-w-md mx-auto">
                You have successfully completed all interactive assignments for this lesson. Keep exploring and learning!
            </p>
            <div class="pt-2">
                <a href="{{ route('posts.feed') }}" class="px-6 py-2.5 bg-cohere-primary text-white text-xs font-mono uppercase tracking-wider rounded-cohere-pill hover:bg-cohere-black transition inline-block">
                    Back to Feed
                </a>
            </div>
        </div>
    @else
        @php
            $task = $tasks[$currentTaskIndex];
            $userCompleted = Auth::user() ? Auth::user()->completedTasks()->where('task_id', $task->id)->exists() : false;
        @endphp

        <!-- Task Header -->
        <div class="flex items-center justify-between border-b border-cohere-hairline pb-4 font-mono">
            <span class="text-xs uppercase tracking-widest text-cohere-slate font-semibold">ASSIGNMENT {{ $currentTaskIndex + 1 }} OF {{ count($tasks) }}</span>
            <span class="text-[10px] bg-cohere-primary/10 text-cohere-primary px-2.5 py-0.5 rounded-cohere-pill uppercase tracking-wider">
                +15 XP
            </span>
        </div>

        <!-- Task Question -->
        <div class="space-y-6">
            
            <!-- Type Label -->
            <span class="inline-block px-3 py-1 bg-cohere-stone border border-cohere-hairline rounded-cohere-xs text-[10px] font-mono uppercase tracking-widest text-cohere-slate">
                @if($task->type === 'multiple_choice') Multiple Choice @elseif($task->type === 'fill_gap') Fill in the Gap @else Matching Game @endif
            </span>

            @if($task->type === 'multiple_choice')
                <!-- A. MULTIPLE CHOICE -->
                <div class="space-y-4">
                    <p class="text-lg font-body text-cohere-primary font-medium leading-relaxed">{{ $task->question_text }}</p>
                    
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($task->answers as $ans)
                            <button wire:click="selectAnswer({{ $ans->id }})" 
                                    @if($showFeedback === 'success') disabled @endif
                                    class="w-full text-left p-4 rounded-cohere-sm border transition text-sm font-body flex items-center justify-between {{ $selectedAnswer == $ans->id ? 'border-cohere-blue bg-cohere-blue/10 text-white font-semibold' : 'border-cohere-border-light bg-white text-cohere-ink hover:border-cohere-blue/50' }}">
                                <span>{{ $ans->answer }}</span>
                                @if($selectedAnswer == $ans->id)
                                    <span class="text-xs font-mono text-cohere-blue font-bold">● Selected</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

            @elseif($task->type === 'fill_gap')
                <!-- B. FILL IN THE GAP -->
                <div class="space-y-6">
                    <p class="text-lg font-body text-cohere-primary font-medium leading-relaxed mb-4">Complete the missing word in the sentence below:</p>
                    
                    @php
                        $segments = $this->getGapSegments($task->question_text);
                    @endphp

                    <div class="p-6 bg-white border border-cohere-border-light rounded-cohere-sm text-lg font-body text-cohere-primary leading-loose flex flex-wrap items-center">
                        <span>{{ $segments['before'] }}</span>
                        <input type="text" 
                               wire:model="gapInput" 
                               @if($showFeedback === 'success') disabled @endif
                               placeholder="..." 
                               class="mx-3 border-b-2 border-t-0 border-x-0 border-cohere-primary focus:border-cohere-violet focus:ring-0 px-2 py-0 text-center font-mono text-cohere-blue bg-cohere-stone/30 w-36 uppercase tracking-wider text-base font-semibold transition" />
                        <span>{{ $segments['after'] }}</span>
                    </div>
                </div>

            @elseif($task->type === 'match_words')
                <!-- C. WORD MATCHING GAME -->
                <div class="space-y-6">
                    <p class="text-base font-body text-cohere-muted leading-relaxed mb-4">Click an English term on the left, then its Russian translation on the right to match them up.</p>
                    
                    <div class="grid grid-cols-2 gap-8 items-start">
                        <!-- Left Column: English -->
                        <div class="space-y-3">
                            <span class="block text-xs font-mono uppercase tracking-wider text-cohere-slate border-b border-cohere-hairline pb-2">English</span>
                            @foreach($shuffledEnglish as $eng)
                                @php
                                    $isMatched = isset($matchedPairs[$eng]);
                                    $isSelected = $selectedEnglish === $eng;
                                    $isWrong = $wrongPair && $wrongPair['english'] === $eng;
                                @endphp
                                <button wire:click="selectEnglishWord('{{ $eng }}')"
                                        @if($isMatched || $showFeedback === 'success') disabled @endif
                                        class="w-full text-center py-3.5 px-4 text-xs font-mono uppercase tracking-wider border rounded-cohere-sm transition {{ $isMatched ? 'bg-cohere-pale-green border-cohere-deep-green/30 text-cohere-deep-green font-semibold' : ($isSelected ? 'bg-cohere-primary border-cohere-primary text-white font-medium' : ($isWrong ? 'bg-red-50 border-red-500 text-red-700 animate-pulse' : 'bg-white border-cohere-border-light text-cohere-primary hover:border-cohere-primary')) }}">
                                    {{ $eng }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Right Column: Russian -->
                        <div class="space-y-3">
                            <span class="block text-xs font-mono uppercase tracking-wider text-cohere-slate border-b border-cohere-hairline pb-2">Russian</span>
                            @foreach($shuffledRussian as $rus)
                                @php
                                    $isMatched = in_array($rus, $matchedPairs);
                                    $isSelected = $selectedRussian === $rus;
                                    $isWrong = $wrongPair && $wrongPair['russian'] === $rus;
                                @endphp
                                <button wire:click="selectRussianWord('{{ $rus }}')"
                                        @if($isMatched || $showFeedback === 'success') disabled @endif
                                        class="w-full text-center py-3.5 px-4 text-xs font-body border rounded-cohere-sm transition {{ $isMatched ? 'bg-cohere-pale-green border-cohere-deep-green/30 text-cohere-deep-green font-semibold' : ($isSelected ? 'bg-cohere-primary border-cohere-primary text-white font-medium' : ($isWrong ? 'bg-red-50 border-red-500 text-red-700 animate-pulse' : 'bg-white border-cohere-border-light text-cohere-primary hover:border-cohere-primary')) }}">
                                    {{ $rus }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Action / Submitting / Feedback Bar -->
        <div class="space-y-4 pt-4 border-t border-cohere-hairline">
            @if($showFeedback)
                <div class="p-4 rounded-cohere-sm text-sm font-body flex items-center justify-between {{ $showFeedback === 'success' ? 'bg-cohere-deep-green/30 text-cohere-blue border border-cohere-blue/10' : 'bg-red-950/30 text-red-300 border border-red-900/30' }}">
                    <div class="flex items-center gap-2">
                        <span>@if($showFeedback === 'success') 🎉 @else ⚠️ @endif</span>
                        <p class="font-medium leading-relaxed">{{ $feedbackMessage }}</p>
                    </div>
                    @if($showFeedback === 'success' && $userCompleted)
                        <span class="text-[10px] font-mono uppercase tracking-wide opacity-80">(Already completed)</span>
                    @endif
                </div>
            @endif

            <div class="flex items-center justify-end gap-3">
                @if($showFeedback === 'success')
                    <button wire:click="nextTask" class="px-6 py-2.5 bg-cohere-deep-green text-white text-xs font-mono uppercase tracking-wider rounded-cohere-pill hover:bg-cohere-black transition">
                        {{ $currentTaskIndex === count($tasks) - 1 ? 'Finish Lesson' : 'Next Assignment →' }}
                    </button>
                @else
                    @if($task->type !== 'match_words')
                        <button wire:click="submitAnswer" class="px-6 py-2.5 bg-cohere-primary text-white text-xs font-mono uppercase tracking-wider rounded-cohere-pill hover:bg-cohere-black transition">
                            Submit Answer
                        </button>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
