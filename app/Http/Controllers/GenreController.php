<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Movie;

class GenreController extends Controller
{
    public function show($genreID) {
        $genre = Genre::findOrFail($genreID);
        // $query = $genre->movies()->paginate(12);

        $query = Movie::query()->with(['genres', 'actors']);

        $query->whereHas('genres', function ($q) use ($genreID) {
                $q->whereIn('genres.id', $genreID);
        });
        $movies = $query->paginate(20)->withQueryString();

        return view('movies.index', compact('movies', 'genres', 'years', 'directors'));
    }
    
}
