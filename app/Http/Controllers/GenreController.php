<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Movie;

class GenreController extends Controller
{
    public function show($genreID) {
        $genre = Genre::findOrFail($genreID);
        $movies = $genre->movies()->paginate(12);
        

        return view('genres.show', compact('genre', 'movies'));
    }
    
}
