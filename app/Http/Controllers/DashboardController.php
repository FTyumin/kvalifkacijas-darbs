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
        $watchlist = auth()->user()->wantToWatch; // Collection of Mark rows
        $ids = $watchlist->pluck('markable_id')->unique();
        $movies = Movie::whereIn('id', $ids)->get();

        $user = \Auth::user();
        $reviews = Review::where('user_id', auth()->user()->id)->get();
        $review_count = count($reviews);

        return view('dashboard', compact('reviews', 'user', 'movies'));
    }
}
