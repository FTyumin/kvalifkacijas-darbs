<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;

use Illuminate\Http\Request;
use Maize\Markable\Models\Favorite;

class DashboardController extends Controller
{
    public function dashboard() {
        $movies = auth()->user()->wantToWatch;

        $user = \Auth::user();
        $reviews = Review::where('user_id', auth()->user()->id)->get();
        $review_count = Review::where('user_id', $user->id)->count();
        $average_review = round(Review::where('user_id', $user->id)->avg('rating'), 2) ?? 0;

        return view('dashboard', compact('reviews', 'user', 'movies', 'average_review'));
    }
}
