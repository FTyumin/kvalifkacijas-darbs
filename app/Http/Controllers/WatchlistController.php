<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Maize\Markable\Models\Favorite;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function watchlist() {
        $movies = Movie::whereHasFavorite(auth()->user())->get();
        return view('watchlist', compact('movies'));
    }

    public function favoriteAdd($id) {
        $movie = Movie::find($id);
        $user = auth()->user();
        Favorite::add($movie, $user);
        session()->flash('success', 'Movie added to watchlist');

        return redirect()->route('watchlist');
    }

    public function favoriteRemove($id) {
        $movie = Movie::find($id);
        $user = auth()->user();
        Favorite::remove($movie, $user);
        session()->flash('success', 'Movie removed from watchlist');

        return redirect()->route('watchlist');
    }
}
