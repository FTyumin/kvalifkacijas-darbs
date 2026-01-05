<?php

namespace App\View\Components;

use Closure;
use App\Models\Movie as MovieModel;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class movieCard extends Component
{
    public MovieModel $movie;
    
    public function __construct(MovieModel $movie)
    {
        $this->movie = $movie;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.movie-card');
    }
}
