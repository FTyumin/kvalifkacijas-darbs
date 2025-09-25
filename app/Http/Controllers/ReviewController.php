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
        // dd($request->product_id);
        Review::create([
            'user_id' => $userId,
            'movie_id' => $request->movie_id, 
            'title' => 'review',
            'description' => $request->comment
        ]);

        return back()->with('success', 'Thank for your review');
        
    }
}
