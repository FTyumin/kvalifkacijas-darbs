<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maize\Markable\Models\Favorite;
use App\Models\WatchHistory;
use App\Models\Movie;
use App\Models\Seen;
use App\Models\WantToWatch;
use Redirect;

class MarkController extends Controller
{
    public function favoriteAdd($id) {
        $movie = Movie::find($id);
        $user = auth()->user();
        Favorite::add($movie, $user);
        session()->flash('success', 'Movie added to watchlist');

        // return redirect()->route('watchlist');
        return Redirect::back()->with('message', 'Movie added to favorites');
    }

    public function favoriteRemove($id) {
        $movie = Movie::find($id);
        $user = auth()->user();
        Favorite::remove($movie, $user);
        session()->flash('success', 'Movie removed from watchlist');

        return Redirect::back()->with('message','Operation Successful !');
    }

    public function watchlistAdd($id) {
        $movie = Movie::findOrFail($id);
        $user = auth()->user();
        WantToWatch::add($movie, $user);
        // dd("debug");
        session()->flash('success', 'Movie added to watchlist');

        return Redirect::back()->with('message','Operation Successful !');
    }

    public function watchlistRemove() {
        $movie = Movie::find($id);
        $user = auth()->user();
        WantToWatch::remove($movie, $user);
        session()->flash('success', 'Movie removed from watchlist');

        return Redirect::back()->with('message','Operation Successful !');
    }

    public function seenAdd($id) {
        $movie = Movie::find($id);
        $user = auth()->user();
        Seen::add($movie, $user);
        session()->flash('success', 'Movie added to seen movies');

        return Redirect::back()->with('message','Operation Successful !');
    }

    public function seenRemove($id) {
        $movie = Movie::find($id);
        $user = auth()->user();
        Seen::remove($movie, $user);
        session()->flash('success', 'Movie removed from seen');

        return Redirect::back()->with('message','Operation Successful !');
    }


}
