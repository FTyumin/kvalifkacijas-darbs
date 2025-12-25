<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Movie;
use Illuminate\Support\Facades\Cache;

class CreateReview extends Component
{
    public $movieId = 0;
    public $movie;
    public $title = '';
    public $comment = '';
    public $rating = null;
    public $spoilers = false;
    public $isEditing = false;
    public $reviewId;
    public $likeCount = 0;

    public array $showSpoilers = [];

    protected $rules = [
        'title' => 'required|string|max:30',
        'rating' => 'required|numeric|min:1|max:5',
        'comment' => 'required|string|max:1000'
    ];

    public function mount(Movie $movie)
    {
        $this->movieId = $movie->id;

        // Check if user has existing review
        if (auth()->check()) {
            $existingReview = Review::where('user_id', auth()->id())
                ->where('movie_id', $this->movieId)
                ->first();
            
            if ($existingReview) {
                $this->loadReview($existingReview);
            }
        }
    }

    public function loadReview($review)
    {
        $this->reviewId = $review->id;
        $this->isEditing = true;
        $this->title = $review->title;
        $this->comment = $review->description;
        $this->rating = $review->rating;
        $this->spoilers = $review->spoilers;
    }

    public function save()
    {
        if (!auth()->check()) {
            session()->flash('warning', 'You must be logged in to write a review');
        }

        $this->validate();
        if($this->isEditing) {
            $review = Review::findOrFail($this->reviewId);
            
            // Check authorization
            if ($review->user_id !== auth()->id()) {
                session()->flash('error', 'You can only edit your own reviews.');
                return;
            }
            
            $review->update([
                'rating' => $this->rating,
                'title' => $this->title,
                'description' => $this->comment,
                'spoilers' => $this->spoilers,
            ]);

            session()->flash('status', 'Review successfully updated.');
        } else {
            if (Review::where('user_id', auth()->id())->where('movie_id', $this->movieId)->exists()) {
                session()->flash('error', 'You have already reviewed this movie.');
                return;
            }
            // Create new review
            Review::create([
                'user_id' => auth()->id(),
                'movie_id' => $this->movieId,
                'rating' => $this->rating,
                'title' => $this->title,
                'description' => $this->comment,
                'spoilers' => $this->spoilers,
            ]);
            
            session()->flash('status', 'Review successfully posted.');
        }
        $this->resetValidation();

        Cache::forget("user:" . auth()->id() . ":recs");
    }

    public function toggleSpoilers(int $reviewId)
    {
        $this->showSpoilers[$reviewId] = !($this->showSpoilers[$reviewId] ?? false);
    }

    public function cancelEdit()
    {
        $this->resetValidation();
        $this->reset(['rating', 'title', 'comment', 'spoilers', 'isEditing', 'reviewId']);
    }

    public function delete()
    {
        if (!$this->reviewId) {
            return;
        }
        
        $review = Review::findOrFail($this->reviewId);
        
        // Check authorization
        if ($review->user_id !== auth()->id()) {
            session()->flash('error', 'You can only delete your own reviews.');
            return;
        }
        
        $review->delete();
        
        Cache::forget("user:" . auth()->id() . ":recs");
        
        $this->reset(['rating', 'title', 'comment', 'spoilers', 'isEditing', 'reviewId']);
        
        session()->flash('status', 'Review successfully deleted.');
    }

    public function edit() {
        $this->validate();
    }

    public function render()
    {
        $movie = Movie::findOrFail($this->movieId);
        return view('livewire.create-review', [
            'movie' => $movie,
            'reviews' => $movie->reviews()
                ->with('user')
                ->latest()
                ->get()
        ]);
    }
}
