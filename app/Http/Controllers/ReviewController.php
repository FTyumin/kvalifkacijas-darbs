<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Request $request) {
        if (\Auth::check()) {
            $userId = \Auth::user()->id;
        } else {
            return back()->with('warning', 'You must be logged in to write a review.');
        }
        Review::create([
            'user_id' => $userId,
            'movie_id' => $request->movie_id, 
            'rating' => $request->rating,
            'title' => 'review',
            'description' => $request->comment,
            'spoilers' => $request->has('spoiler')
        ]);

        return back()->with('success', 'Thank for your review');
    }

    public function index(Request $request) {
        $reviews = Review::with('user')->get();

        return view('reviews.index', compact('reviews'));
    }

    public function show(Review $review) {
        return view('reviews.show', compact('review'));
    }
}
