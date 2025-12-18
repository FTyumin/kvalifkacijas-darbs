<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Maize\Markable\Models\Favorite;
use App\Models\Movie;
use App\Models\Seen;
use App\Models\Person;
use App\Models\WantToWatch;
use Illuminate\Support\Facades\Auth;

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
            //recs are based on favorites
            Cache::forget("user:{$user->id}:recs");
            session()->flash('success', 'Movie added to favorites!');
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
            Cache::forget("user:{$user->id}:recs");
            session()->flash('success', 'Movie added to watchlist!');
        }

        return back();
    }

    public function seenToggle($movieId)
    {
        $movie = Movie::find($movieId);
        $user = auth()->user();

        if ($user->seenMovies()->where('markable_id', $movieId)->exists()) {
            Seen::remove($movie, $user);
        } else {
            Seen::add($movie, $user);
            //recommendations don't include movies user has alredy seen
            Cache::forget("user:{$user->id}:recs");
            session()->flash('success', 'Movie added to seen!');

        }

        return back();
    }

    public function favoritePersonToggle($Id) {
        $user = Auth::user();
        $person = Person::find($Id);

        //separating actors and directors
        if($person->type == 'actor') {
            $user->favoriteActors()->toggle($Id);

        } else {
            $user->favoriteDirectors()->toggle($Id);
        }   
        return redirect()->back();
    }
}
