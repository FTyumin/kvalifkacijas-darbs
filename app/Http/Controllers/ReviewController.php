<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    public function index(Request $request) {
        $reviews = Review::with('user')->get();
        return view('reviews.index', compact('reviews'));
    }

    public function show(Review $review) {
        $review->load('comments');
        return view('reviews.show', compact('review'));
    }

    public function showUserReviews(User $user) {
        $reviews = Review::where('user_id', $user->id)->get();
        return view('reviews.user', compact('reviews', 'user'));
    }

    public function toggleLike(Review $review)
    {
        $user = auth()->user();
        if ($review->likedBy()->where('user_id', $user->id)->exists()) {
            // Unlike
            $review->likedBy()->detach($user->id);
        } else {
            // Like
            $review->likedBy()->attach($user->id);
        }
        return back();
    }
}
