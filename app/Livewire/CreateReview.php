<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Movie;
use Illuminate\Support\Facades\Cache;

class CreateReview extends Component
{
   public $movieId;
   public $movie;
    public $title = '';
    public $comment = '';
    public $rating = null;
    public $spoilers = false;

    protected $rules = [
        'title' => 'required|string|max:30',
        'rating' => 'required|numeric|min:1|max:5',
        'comment' => 'required|string|max:1000'
    ];

    public function mount(Movie $movie)
    {
        $this->movieId = $movie->id;
    }

    public function save()
    {
        if (\Auth::check()) {
            $userId = \Auth::user()->id;
        } else {
            session()->flash('warning', 'You must be logged in to write a review.');
            return;
        }

        $this->validate();
        $this->resetValidation();
        Review::create([
            'user_id' => $userId,
            'movie_id' => $this->movieId, 
            'rating' => $this->rating,
            'title' => $this->title,
            'description' => $this->comment,
            'spoilers' => $this->spoilers,
        ]);

        Cache::forget("user:{$userId}:recs");

        $this->reset(['rating', 'title', 'comment', 'spoilers']);

        session()->flash('status', 'Review successfully updated.');
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
