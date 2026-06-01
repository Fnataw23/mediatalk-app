<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Task;
use App\Models\TaskAnswer;
use Illuminate\Support\Facades\Auth;

class TaskRunner extends Component
{
    public Post $post;
    public $tasks = [];
    public $currentTaskIndex = 0;
    
    // Multiple Choice State
    public $selectedAnswer = null;
    
    // Fill in the Gaps State
    public $gapInput = '';
    
    // Match Words State
    public $shuffledEnglish = [];
    public $shuffledRussian = [];
    public $selectedEnglish = null;
    public $selectedRussian = null;
    public $matchedPairs = []; // [english => russian]
    public $wrongPair = null; // for temporary shaking effect
    
    // Feedback
    public $showFeedback = null; // success, error
    public $feedbackMessage = '';
    public $showConfetti = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->tasks = $post->tasks()->with('answers')->get();
        $this->initializeTask();
    }

    public function initializeTask()
    {
        $this->reset(['selectedAnswer', 'gapInput', 'selectedEnglish', 'selectedRussian', 'matchedPairs', 'wrongPair', 'showFeedback', 'feedbackMessage', 'showConfetti']);
        
        if (empty($this->tasks) || !isset($this->tasks[$this->currentTaskIndex])) {
            return;
        }

        $task = $this->tasks[$this->currentTaskIndex];

        if ($task->type === 'match_words') {
            $this->shuffledEnglish = $task->answers->pluck('answer')->shuffle()->toArray();
            $this->shuffledRussian = $task->answers->pluck('matching_translation')->shuffle()->toArray();
        }
    }

    public function getGapSegments($text)
    {
        preg_match('/\[(.*?)\]/', $text, $matches);
        if (empty($matches)) {
            return ['before' => $text, 'gap' => '', 'after' => ''];
        }
        $parts = explode($matches[0], $text);
        return [
            'before' => $parts[0] ?? '',
            'gap' => $matches[1] ?? '',
            'after' => $parts[1] ?? '',
        ];
    }

    public function selectAnswer($answerId)
    {
        $this->selectedAnswer = $answerId;
        $this->showFeedback = null;
    }

    // Match Words click logic
    public function selectEnglishWord($word)
    {
        if (isset($this->matchedPairs[$word])) return;
        $this->selectedEnglish = $word;
        $this->wrongPair = null;
        $this->checkMatch();
    }

    public function selectRussianWord($word)
    {
        if (in_array($word, $this->matchedPairs)) return;
        $this->selectedRussian = $word;
        $this->wrongPair = null;
        $this->checkMatch();
    }

    protected function checkMatch()
    {
        if (!$this->selectedEnglish || !$this->selectedRussian) {
            return;
        }

        $task = $this->tasks[$this->currentTaskIndex];
        
        // Verify if this is the correct pairing in the DB
        $isCorrect = $task->answers()
            ->where('answer', $this->selectedEnglish)
            ->where('matching_translation', $this->selectedRussian)
            ->exists();

        if ($isCorrect) {
            $this->matchedPairs[$this->selectedEnglish] = $this->selectedRussian;
            $this->selectedEnglish = null;
            $this->selectedRussian = null;

            // Check if all pairs are successfully matched
            if (count($this->matchedPairs) === count($task->answers)) {
                $user = Auth::user();
                $alreadyCompleted = $user ? $user->completedTasks()->where('task_id', $task->id)->exists() : false;
                $this->handleSuccess($task, $user, $alreadyCompleted);
            }
        } else {
            // Set error trigger for Alpine UI shake
            $this->wrongPair = [
                'english' => $this->selectedEnglish,
                'russian' => $this->selectedRussian
            ];
            $this->selectedEnglish = null;
            $this->selectedRussian = null;
        }
    }

    public function submitAnswer()
    {
        $user = Auth::user();
        if (!$user) {
            $this->showFeedback = 'error';
            $this->feedbackMessage = 'Please log in to complete tasks and earn XP.';
            return;
        }

        $task = $this->tasks[$this->currentTaskIndex];
        $alreadyCompleted = $user->completedTasks()->where('task_id', $task->id)->exists();

        // 1. Multiple Choice
        if ($task->type === 'multiple_choice') {
            if (!$this->selectedAnswer) {
                $this->showFeedback = 'error';
                $this->feedbackMessage = 'Please select an option before submitting.';
                return;
            }

            $ans = TaskAnswer::find($this->selectedAnswer);
            if ($ans && $ans->is_correct) {
                $this->handleSuccess($task, $user, $alreadyCompleted);
            } else {
                $this->showFeedback = 'error';
                $this->feedbackMessage = 'Incorrect option. Take another look and try again!';
            }
        }
        // 2. Fill in the Gaps
        elseif ($task->type === 'fill_gap') {
            $correctAns = $task->answers()->where('is_correct', true)->first();
            if ($correctAns) {
                $userInput = trim(strtolower($this->gapInput));
                $targetInput = trim(strtolower($correctAns->answer));

                if ($userInput === $targetInput) {
                    $this->handleSuccess($task, $user, $alreadyCompleted);
                } else {
                    $this->showFeedback = 'error';
                    $this->feedbackMessage = 'Incorrect word or spelling spelling. Try again!';
                }
            }
        }
    }

    protected function handleSuccess($task, $user, $alreadyCompleted)
    {
        $this->showFeedback = 'success';
        $this->feedbackMessage = 'Excellent! That is 100% correct.';
        $this->showConfetti = true;
        
        $this->dispatch('task-solved');

        if ($user && !$alreadyCompleted) {
            $user->completedTasks()->attach($task->id);
            \App\Services\XPService::awardXp($user, 15, "Solved interactive assignment on post: " . $this->post->title);
        }
    }

    public function nextTask()
    {
        $this->currentTaskIndex++;
        $this->initializeTask();
    }

    public function render()
    {
        return view('livewire.task-runner');
    }
}
