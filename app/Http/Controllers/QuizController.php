<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show() {
        return view('quiz');
    }

    public function store(Request $request) {
        $user = Auth::user();

        $user->quiz_completed = true;
        $user->save();

        return redirect()->route('home')->with('success', 'Quiz completed!');
    }
}
