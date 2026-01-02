<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
class PeopleController extends Controller
{
    public function show(Person $person) {
        return view('people.show', compact('person'));
    }
}
