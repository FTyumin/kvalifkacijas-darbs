<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Movie;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard() {

        // select top reviews, lists
        $userWithMostFollowers = User::withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->first();

        $topReview = Review::withCount('likedBy')
            ->orderBy('liked_by_count', 'desc')
            ->first();

        // movies with most favorites, etc
        $mostFavorites = Movie::withCount('favoriters')
            ->orderBy('favoriters_count', 'desc')
            ->take(5)
            ->get();

        $mostWatched = Movie::withCount('watchers')
            ->orderBy('watchers_count', 'desc')
            ->take(5)
            ->get();
            
        return view('admin');
    }
}
