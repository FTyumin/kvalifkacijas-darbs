<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Comment;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get IDs of users that the current user follows
        $followingIds = $user->followees()
            ->pluck('followee_id')
            ->toArray();
        
        // Get reviews, comments from followed users 
        $reviews = Review::whereIn('user_id', $followingIds)
            ->with(['user', 'movie']) 
            ->latest()
            ->paginate(20);

        $comments = Comment::whereIn('user_id', $followingIds)
            ->with(['user', 'review']) 
            ->latest()
            ->paginate(20);

        return view('feed.index', compact('reviews'));
    }
}
