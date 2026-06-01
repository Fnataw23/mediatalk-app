<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class PostReactions extends Component
{
    public Post $post;
    
    // Counts
    public $usefulCount = 0;
    public $funnyCount = 0;
    public $interestingCount = 0;
    
    // User states
    public $userUseful = false;
    public $userFunny = false;
    public $userInteresting = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->loadCounts();
        $this->loadUserStates();
    }

    public function loadCounts()
    {
        $this->usefulCount = $this->post->reactions()->where('reaction_type', 'useful')->count();
        $this->funnyCount = $this->post->reactions()->where('reaction_type', 'funny')->count();
        $this->interestingCount = $this->post->reactions()->where('reaction_type', 'interesting')->count();
    }

    public function loadUserStates()
    {
        if ($user = Auth::user()) {
            $this->userUseful = $this->post->reactions()->where('user_id', $user->id)->where('reaction_type', 'useful')->exists();
            $this->userFunny = $this->post->reactions()->where('user_id', $user->id)->where('reaction_type', 'funny')->exists();
            $this->userInteresting = $this->post->reactions()->where('user_id', $user->id)->where('reaction_type', 'interesting')->exists();
        } else {
            $this->userUseful = false;
            $this->userFunny = false;
            $this->userInteresting = false;
        }
    }

    public function toggleReaction($type)
    {
        $user = Auth::user();
        if (!$user) {
            $this->dispatch('notify', ['message' => 'Please log in to react.', 'type' => 'error']);
            return;
        }

        if (!in_array($type, ['useful', 'funny', 'interesting'])) {
            return;
        }

        $existing = $this->post->reactions()
            ->where('user_id', $user->id)
            ->where('reaction_type', $type)
            ->first();

        if ($existing) {
            // Remove reaction
            $existing->delete();
            
            if ($type === 'useful') {
                $this->userUseful = false;
                $this->usefulCount--;
            } elseif ($type === 'funny') {
                $this->userFunny = false;
                $this->funnyCount--;
            } elseif ($type === 'interesting') {
                $this->userInteresting = false;
                $this->interestingCount--;
            }
        } else {
            // Add reaction
            $this->post->reactions()->create([
                'user_id' => $user->id,
                'reaction_type' => $type,
            ]);

            if ($type === 'useful') {
                $this->userUseful = true;
                $this->usefulCount++;
            } elseif ($type === 'funny') {
                $this->userFunny = true;
                $this->funnyCount++;
            } elseif ($type === 'interesting') {
                $this->userInteresting = true;
                $this->interestingCount++;
            }

            // Award +2 XP!
            \App\Services\XPService::awardXp($user, 2, "Reacted to post: " . $this->post->title);
        }
    }

    public function render()
    {
        return view('livewire.post-reactions');
    }
}
