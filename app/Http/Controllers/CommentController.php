<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function create(Request $request) {

        Comment::create([
            'user_id' => Auth::user()->id,
            'review_id' => $request->review_id,
            'text' => $request->description,
        ]);
    }
}
