<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class PostComments extends Component
{
    use WithPagination;

    public Post $post;
    
    // Inputs
    public $commentText = '';
    public $replyingToId = null;
    public $replyText = '';

    protected $rules = [
        'commentText' => 'required|string|min:3|max:1000',
        'replyText' => 'required|string|min:3|max:1000',
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function addComment()
    {
        $user = Auth::user();
        if (!$user) {
            $this->dispatch('notify', ['message' => 'Please log in to leave a comment.', 'type' => 'error']);
            return;
        }

        $this->validateOnly('commentText', [
            'commentText' => 'required|string|min:3|max:1000',
        ]);

        $comment = $this->post->comments()->create([
            'user_id' => $user->id,
            'parent_id' => null,
            'content' => $this->commentText,
        ]);

        $this->commentText = '';

        // Award +5 XP!
        \App\Services\XPService::awardXp($user, 5, "Left a comment on post: " . $this->post->title);
    }

    public function toggleReply($commentId)
    {
        if ($this->replyingToId === $commentId) {
            $this->replyingToId = null;
        } else {
            $this->replyingToId = $commentId;
            $this->replyText = '';
        }
    }

    public function addReply($parentId)
    {
        $user = Auth::user();
        if (!$user) {
            $this->dispatch('notify', ['message' => 'Please log in to reply.', 'type' => 'error']);
            return;
        }

        $this->validateOnly('replyText', [
            'replyText' => 'required|string|min:3|max:1000',
        ]);

        $this->post->comments()->create([
            'user_id' => $user->id,
            'parent_id' => $parentId,
            'content' => $this->replyText,
        ]);

        $this->replyingToId = null;
        $this->replyText = '';

        // Award +3 XP!
        \App\Services\XPService::awardXp($user, 3, "Replied to a comment");
    }

    public function toggleLike($commentId)
    {
        $user = Auth::user();
        if (!$user) {
            $this->dispatch('notify', ['message' => 'Please log in to like comments.', 'type' => 'error']);
            return;
        }

        $existing = CommentLike::where('user_id', $user->id)
            ->where('comment_id', $commentId)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            CommentLike::create([
                'user_id' => $user->id,
                'comment_id' => $commentId,
            ]);
            
            // Optional minor +2 XP for positive community interaction
            \App\Services\XPService::awardXp($user, 2, "Liked a comment");
        }
    }

    public function deleteComment($commentId)
    {
        $user = Auth::user();
        if (!$user) return;

        $comment = Comment::find($commentId);
        if (!$comment) return;

        // Verify if owner or administrator
        if ($comment->user_id === $user->id || $user->is_admin) {
            $comment->delete();
            $this->dispatch('notify', ['message' => 'Comment deleted.', 'type' => 'success']);
        }
    }

    public function render()
    {
        // Load root comments and eager-load nested relationships
        $comments = $this->post->comments()
            ->whereNull('parent_id')
            ->with(['user', 'likes', 'replies.user', 'replies.likes', 'replies.replies.user', 'replies.replies.likes'])
            ->latest()
            ->paginate(15);

        return view('livewire.post-comments', [
            'comments' => $comments,
        ]);
    }
}
