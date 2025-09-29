<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Director;

class DirectorController extends Controller
{
    public function show($directorID) {
        $director = Director::findOrFail($directorID);

        return view('directors.show', compact('director'));
    }
}
