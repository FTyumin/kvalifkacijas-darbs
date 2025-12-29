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

    public function search(Request $request) {
        $search = $request->input('search');

        $people = DB::table('persons')
            ->where('first_name', 'like', "%{$search}%")
            ->where('type', 'actor')
            ->orWhere('last_name', 'like', "%{$search}%")
            ->get();

        return response()->json($people);
    }

    public function directorSearch(Request $request) {
        $search = $request->input('search');

        $people = DB::table('persons')
            ->where('first_name', 'like', "%{$search}%")
            ->where('type', 'director')
            ->orWhere('last_name', 'like', "%{$search}%")
            ->get();

        return response()->json($people);
    }
}
