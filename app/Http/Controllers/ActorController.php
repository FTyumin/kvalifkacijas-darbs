<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actor;

class ActorController extends Controller
{
    public function show($actorID) {
        $actor = Actor::findOrFail($actorID);
        $actor->load('movies');
        return view('actors.show', compact('actor'));
    }

    public function display() {
        return view('actors.display');
    }
}
