<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovieList;

class ListController extends Controller
{
    public function store(Request $request) {
        $data = $request->all();
        $userId = \Auth::id();

        $request->validate(
            [
                'name' => 'required|string|max:30',
                'description' => 'required|string|max:300'
            ],
            [
                'name.required' => 'Please enter a list name.',
                'name.max' => 'List names may be at most 30 characters.',
                'description.required' => 'Please enter a description.',
                'description.max' => 'Descriptions may be at most 300 characters.',
            ]
        );

        MovieList::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'description' => $data['description'],
            'is_private' => $request->has('is_private'),
        ]);
        session()->flash('success', 'List created!');
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
        if($list->movies->contains($movieId)) {
            return back()->with('warning', 'Movie is already in list');
        } else {
            $list->addMovie($movieId);

        }
        
        return back()->with('success', 'Movie added to list');
    }

    public function remove(MovieList $list, $movieId) {

        $list->removeMovie($movieId);
        return back()->with('message','Movie removed!');
    }

    public function edit(MovieList $list) {
        return view('lists.edit', compact('list'));
    }

    public function update(Request $request, MovieList $list) {
        $request->validate(
            [
                'name' => 'required|string|max:30',
                'description' => 'required|string|max:300'
            ],
            [
                'name.required' => 'Please enter a list name.',
                'name.max' => 'List names may be at most 30 characters.',
                'description.required' => 'Please enter a description.',
                'description.max' => 'Descriptions may be at most 300 characters.',
            ]
        );
        
        $list->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_private' => $request->boolean('is_private'),
        ]);

        session()->flash('success', 'List updated!');
        return redirect()->route('lists.show', compact('list'));
    }

    public function destroy(MovieList $list) {
        $list->delete();

        session()->flash('success', 'List deleted!');
        return redirect('lists');
    }
}
