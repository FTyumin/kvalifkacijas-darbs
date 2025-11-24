<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRelationship;

class UserRelationshipController extends Controller
{
    public function followers($userId)
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers()->with('follower')->get();

        return response()->json($followers);
    }

    // retrieve followees of a given user
    public function followees($userId)
    {
        $user = User::findOrFail($userId);
        $followees = $user->followees()->with('followee')->get();

        return response()->json($followees);
    }

    public function follow(Request $request, $userId)
    {
        $followerId = $request->user()->id; // The current user
        // Prevent self-following
        if ($followerId == $userId) {
            return response()->json(['message' => 'You cannot follow yourself'], 400);
        }
        
        // Check if already following
        $exists = UserRelationship::where('follower_id', $followerId)
            ->where('followee_id', $userId)
            ->exists();
        
        if ($exists) {
            return response()->json(['message' => 'Already following'], 400);
        }
        
        // Create the relationship
        UserRelationship::create([
            'follower_id' => $followerId,
            'followee_id' => $userId
        ]);
        
        return response()->json(['message' => 'Successfully followed', 'following' => true]);
    }

    public function unfollow(Request $request, $userId)
    {
        $followerId = $request->user()->id;
        
        UserRelationship::where('follower_id', $followerId)
            ->where('followee_id', $userId)
            ->delete();
        
        return response()->json(['message' => 'Successfully unfollowed', 'following' => false]);
    }
}
