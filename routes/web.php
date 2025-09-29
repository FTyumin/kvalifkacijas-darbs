<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/movies', [MovieController::class, 'show'])->name('movie.show');

Route::get('/director', [DirectorController::class, 'show'])->name('director.show');

Route::get('/actors', [ActorController::class, 'show'])->name('actor.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/search', [MovieController::class, 'search'])->name('movies.search');

require __DIR__.'/auth.php';
