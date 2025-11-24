<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
}
