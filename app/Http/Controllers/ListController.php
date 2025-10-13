<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\User;
use App\Models\MovieList;


class ListController extends Controller
{
    public function store(Request $request) {
        $data = $request->all();
        $userId = \Auth::id();

        MovieList::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'description' => $data['description'],
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('lists.index');
    }

    public function show(MovieList $list) {
        return view('lists.show', compact('list'));
    }

    public function index() {
        $lists = MovieList::all();

        return view('lists.index', compact('lists'));
    }

    public function share() {

    }

    public function create() {
        return view('lists.create');
    }

    public function add(Request $request, $movieId) {
        $list = MovieList::find($request->listId);
        $list->addMovie($movieId);

        return back()->with('success', 'Movie added to list');
    }

    public function remove(Request $request, $movieId) {
        $list = MovieList::find($request->list_id);
        $list->removeMovie($movieId);

        return back()->with('message','Movie removed!');
    }
}
