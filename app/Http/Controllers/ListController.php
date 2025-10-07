<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\User;
use App\Models\MovieList;

class ListController extends Controller
{
    public function store(Request $request) {
        dd($request);

        // MovieList::create([
        //     'name'=>
        // ]);
        return redirect()->route('dashboard');
    }

    public function show() {

    }

    public function index() {

    }

    public function share() {

    }

    public function create() {
        return view('lists.create');
    }
}
