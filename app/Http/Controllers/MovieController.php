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

        $results = Movie::with(['director', 'actors'])
            ->where('name', 'like', "%{$search}%")
            ->orWhereHas('director', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('actors', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->get();


        return view('movies.index', compact('results', 'search'));
    }
}
