<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;

class GenreController extends Controller
{
    public function show($genreID) {
        $genre = Genre::findOrFail($genreID);
        $movies = $genre->movies;

        return view('genres.show', compact('genre', 'movies'));
    }
    
}
