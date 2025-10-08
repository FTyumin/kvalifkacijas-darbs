<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\User;
use App\Models\MovieList;
use App\Models\List;


class ListController extends Controller
{
    public function store(Request $request) {
        $data = $request->all();
        // dd($data['is_public']);
        List::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'is_public' => $request->has('is_public'),
        ]);

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
