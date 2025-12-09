<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class PeopleController extends Controller
{
    public function show(Person $person) {
        return view('people.show', compact('person'));
    }

    //TODO:
    // favorite actor/director button
}
