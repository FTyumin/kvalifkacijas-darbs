<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;

use Illuminate\Http\Request;
use Maize\Markable\Models\Favorite;

class DashboardController extends Controller
{
    public function dashboard() {
        $movies = Movie::whereHasFavorite(auth()->user())->get();

        $userId = \Auth::user()->id;
        $user = \Auth::user();
        $reviews = Review::where('user_id', $userId)->get();

        $review_count = count($reviews);
    

        return view('dashboard', compact('movies', 'reviews', 'user'));
    }
}
