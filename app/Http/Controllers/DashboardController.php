<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Maize\Markable\Models\Favorite;

class DashboardController extends Controller
{
    public function dashboard() {
        $movies = Movie::whereHasFavorite(auth()->user())->get();


        return view('dashboard', compact('movies'));
    }
}
