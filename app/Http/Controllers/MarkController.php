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
    public function favoriteToggle($movieId)
    {
        $movie = Movie::find($movieId);
        $user = auth()->user();

        if ($user->favorites()->where('markable_id', $movieId)->exists()) {
            Favorite::remove($movie, $user);
        } else {
            Favorite::add($movie, $user);
        }

        return back();
    }

    public function watchlistToggle($movieId)
    {
        $movie = Movie::find($movieId);
        $user = auth()->user();

        if ($user->wantToWatch()->where('markable_id', $movieId)->exists()) {
            WantToWatch::remove($movie, $user);
        } else {
            WantToWatch::add($movie, $user);
        }

        return back();
    }

    public function seenToggle($movieId)
    {
        $movie = Movie::find($movieId);
        $user = auth()->user();

        if ($user->seenMovies()->where('markable_id', $movieId)->exists()) {
            // Already seen → remove
            Seen::remove($movie, $user);
        } else {
            // Not seen → add
            Seen::add($movie, $user);
        }

        return back();
    }


}
