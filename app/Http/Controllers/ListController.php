<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'is_private' => $request->has('is_private'),
        ]);

        return redirect()->route('lists.index');
    }

    public function show(MovieList $list) {
        if (!$list->canView(auth()->user())) {
            abort(403, 'This list is private.');
        }

        return view('lists.show', compact('list'));
    }

    public function index() {
         $lists = MovieList::visibleTo(auth()->user())
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('lists.index', compact('lists'));
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
