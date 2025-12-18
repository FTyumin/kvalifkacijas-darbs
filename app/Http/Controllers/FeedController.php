<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get IDs of users that the current user follows
        $followingIds = $user->followees()
            ->pluck('followee_id')
            ->toArray();

        // Get activities of users, that current user follows
        $activities = Activity::whereIn('user_id', $followingIds)
            ->with('user')
            ->latest()
            ->paginate(20);
        // dd($activities[3]->user);
        return view('feed.index', compact('activities'));
    }
}
