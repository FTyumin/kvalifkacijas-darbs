<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function create(Request $request) {

        if (\Auth::check()) {
            $userId = \Auth::user()->id;
        } else {
            return back()->with('warning', 'You must be logged in to write a comment.');
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        Comment::create([
            'user_id' => $userId,
            'review_id' => $request->review_id,
            'description' => $request->comment,
        ]);

        return back();
    }
}
