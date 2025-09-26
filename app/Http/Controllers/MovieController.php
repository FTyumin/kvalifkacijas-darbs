<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function show() {
        return view('movies.show');
    }

    public function search(Request $request) {
        $search = $request->input('search');
        $results = Movie::SearchByNameOrDirector($search)->get();

        return view('movies.index', compact('results'));
    }
}
