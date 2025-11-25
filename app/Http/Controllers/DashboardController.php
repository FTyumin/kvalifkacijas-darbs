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
        // $watchlist = Movie::whereHasReaction(
        //     auth()->user(),
        //     'WantToWatch'
        // )->get();
        $watchlist = auth()->user()->wantToWatch;
        $userId = \Auth::user()->id;
        $user = \Auth::user();
        $reviews = Review::where('user_id', $userId)->get();

        $review_count = count($reviews);
        // dd($watchlist);
        // dd(auth()->user()->wantToWatch);

        return view('dashboard', compact('reviews', 'user', 'watchlist'));
    }
}
