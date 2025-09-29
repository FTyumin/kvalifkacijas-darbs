<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actor;
use App\Models\Director;
use App\Models\Genre;
use App\Models\Movie;

class HomeController extends Controller
{
    public function home() {
        $movies = Movie::all()->take(4);
        $genres = Genre::all()->take(4);
        $actors = Actor::all()->take(4);

        return view('home', compact('movies', 'genres', 'actors'));
    }
}
