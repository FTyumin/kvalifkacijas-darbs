<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
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
