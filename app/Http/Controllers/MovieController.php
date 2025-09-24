<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function show($movieID) {
        $movie = Movie::findOrFail($movieID);
        $movie->load('genres');
        return view('movies.show', compact('movie'));
    }

    public function display() {
        return view('movies.display');
    }
}
