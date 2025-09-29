<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Maize\Markable\Models\Bookmark;

class BookmarkController extends Controller
{
    public function bookmarkList() {
        $movies = Movie::whereHasBookmark(auth()->user())->get();
        
        return view('bookmark', compact('movies'));
    }

    public function bookmarkAdd($id) {
        $movie = Movie::find($id);
        $user = auth()->user();
        Bookmark::add($movie, $user);
        session()->flash('success', 'Movie added to watchlist');

        return redirect()->route('bookmark');
    }

    public function bookmarkRemove($id) {
        $movie = Movie::find($id);
        $user = auth()->user();
        Bookmark::remove($movie, $user);
        session()->flash('success', 'Movie removed from watchlist');

        return Redirect::back()->with('message','Operation Successful !');
    }
}
