<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Debate;
use App\Models\DebateVote;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;

class DebateCard extends Component
{
    public ?Debate $debate = null;
    public $hasVoted = false;
    public $userVote = null;
    
    public $showFeedback = null;
    public $feedbackMessage = '';

    public function mount()
    {
        $this->debate = Debate::where('active', true)->first() ?? Debate::latest()->first();
        $this->loadUserVote();
    }

    public function loadUserVote()
    {
        if (!$this->debate) return;

        if ($user = Auth::user()) {
            $vote = $this->debate->votes()->where('user_id', $user->id)->first();
            if ($vote) {
                $this->hasVoted = true;
                $this->userVote = $vote->vote;
            }
        }
    }

    public function castVote($value)
    {
        $user = Auth::user();
        if (!$user) {
            $this->showFeedback = 'error';
            $this->feedbackMessage = 'Please log in to cast your vote.';
            return;
        }

        if (!$this->debate) return;

        if (!in_array($value, ['yes', 'no'])) return;

        // Check if already voted
        $existing = $this->debate->votes()->where('user_id', $user->id)->first();
        if ($existing) {
            $this->showFeedback = 'error';
            $this->feedbackMessage = 'You have already voted in this debate.';
            return;
        }

        // Log the vote
        $this->debate->votes()->create([
            'user_id' => $user->id,
            'vote' => $value,
        ]);

        $this->hasVoted = true;
        $this->userVote = $value;
        $this->showFeedback = 'success';
        $this->feedbackMessage = 'Your vote has been counted! +10 XP awarded.';

        // Award +10 XP!
        \App\Services\XPService::awardXp($user, 10, "Voted in weekly debate: " . $this->debate->title);
    }

    /**
     * Resolve a matching post for comments integration.
     */
    #[Computed]
    public function matchingPost(): ?Post
    {
        if (!$this->debate) return null;

        $category = Category::firstOrCreate(
            ['slug' => 'weekly-debate'],
            ['title' => 'Weekly Debate']
        );

        return Post::firstOrCreate(
            ['slug' => Str::slug($this->debate->title)],
            [
                'category_id' => $category->id,
                'title' => $this->debate->title,
                'description' => $this->debate->description,
                'media_type' => 'image',
                'media_url' => 'https://images.unsplash.com/photo-1577563906417-a045ef5012a9',
                'level' => 'B1',
            ]
        );
    }

    public function render()
    {
        return view('livewire.debate-card');
    }
}
