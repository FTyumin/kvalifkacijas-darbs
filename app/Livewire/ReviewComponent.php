<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Review;
class ReviewComponent extends Component
{
    public Review $review;

    public function mount(Review $review) 
    {
        $this->review = $review;
    }

    public function toggleLike()
    {
        $user = auth()->user();
        
        if ($this->review->likedBy()->where('user_id', $user->id)->exists()) {
            // Unlike
            $this->review->likedBy()->detach($user->id);
        } else {
            // Like
            $this->review->likedBy()->attach($user->id);
        }
    }

    public function render()
    {
        return view('livewire.review-component');
    }
}
