<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    public function create(Request $request) {
        if (\Auth::check()) {
            $userId = \Auth::user()->id;
        } else {
            return back()->with('warning', 'You must be logged in to write a review.');
        }

        $request->validate([
            'title' => 'required|string|max:30',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string|max:1000'
        ]);

        // dd($request->title);
        Review::create([
            'user_id' => $userId,
            'movie_id' => $request->movie_id, 
            'rating' => $request->rating,
            'title' => $request->title,
            'description' => $request->comment,
            'spoilers' => $request->has('spoiler')
        ]);

        Cache::forget("user:{$userId}:recs");

        return back()->with('success', 'Thank for your review');
    }

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
