<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Livewire\WithPagination;

class Feed extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $level = '';
    public $sortBy = 'new';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'level' => ['except' => ''],
        'sortBy' => ['except' => 'new'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingLevel()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function setCategory($slug)
    {
        $this->category = $slug === $this->category ? '' : $slug;
    }

    public function setLevel($level)
    {
        $this->level = $level === $this->level ? '' : $level;
    }

    public function setSort($sort)
    {
        $this->sortBy = $sort;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category', 'level', 'sortBy']);
    }

    public function render()
    {
        $query = Post::query()
            ->whereHas('category', function ($q) {
                $q->where('slug', '!=', 'weekly-debate');
            })
            ->with(['category'])
            ->withCount(['comments', 'reactions']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('vocabularies', function ($sub) {
                      $sub->where('word', 'like', '%' . $this->search . '%')
                          ->orWhere('translation', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->category) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->category);
            });
        }

        if ($this->level) {
            $query->where('level', $this->level);
        }

        if ($this->sortBy === 'popular') {
            $query->orderBy('reactions_count', 'desc');
        } elseif ($this->sortBy === 'discussed') {
            $query->orderBy('comments_count', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return view('livewire.feed', [
            'posts' => $query->paginate(6),
            'categories' => Category::where('slug', '!=', 'weekly-debate')->get(),
        ]);
    }
}
