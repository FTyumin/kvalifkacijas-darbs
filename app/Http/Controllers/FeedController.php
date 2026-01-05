<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\UserRelationship;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get activities of users, that current user follows
        $activities = Activity::query()
            ->with('user', 'activityable')
            ->where(function ($query) use ($user) {
                    // Get IDs of user's following
                    $followingIds = $user->followees()->pluck('followee_id')->toArray();
                    
            // Activities from following 
            $query->where(function ($q) use ($followingIds) {
                $q->whereIn('user_id', $followingIds)
                ->where('activityable_type', '!=', UserRelationship::class);
            })
            // Follow events where other user follows current user
            ->orWhere(function ($q) use ($user) {
                $q->where('activityable_type', UserRelationship::class)
                ->whereHasMorph('activityable', UserRelationship::class,
                    fn ($rel) => $rel->where('followee_id', $user->id)
                );
            });
        })
        ->latest()
        ->paginate(20)
        ->withQueryString();

        return view('feed.index', compact('activities'));
    }

    // get all user activities for admin
    public function adminFeed(Request $request) {
        $activities = Activity::with('user')->latest()->paginate();

        return view('feed.index', compact('activities'));
    }
}
