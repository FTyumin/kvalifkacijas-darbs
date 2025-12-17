<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Movie;
use App\Models\Suggestion;
use App\Models\User;
use App\Notifications\SuggestionAccepted;
use Illuminate\Support\Facades\Artisan;

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

        $suggestions = Suggestion::where('accepted', '0')->get();

        return view('admin', compact('suggestions', 'userWithMostFollowers', 'topReview', 
            'mostFavorites', 'mostWatched'));
    }

    public function approveSuggestion(Suggestion $suggestion) {
        $suggestion->update(['accepted' => 1]);
        $user = $suggestion->user;
        $user->notify(new SuggestionAccepted());
        return back();
    }

    public function rejectSuggestion(Suggestion $suggestion) {
        $suggestion->update(['rejected' => 1]);
        $user = $suggestion->user;
        $user->notify(new SuggestionAccepted());
        return back();
    }

    public function loadMovies() {
        $count = 100;
        Artisan::call('app:insert-data ', [
            'count' => $count,
        ]);
    }
}
