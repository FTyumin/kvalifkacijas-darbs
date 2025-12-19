<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Review;

class ReviewComponent extends Component
{
   
    public Review $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.review');
    }
}
