<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ReviewController;

use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('movies', MovieController::class)->only(['index','show']);
Route::resource('directors', DirectorController::class)->only(['index','show']);
Route::resource('genres', GenreController::class)->only(['index', 'show']);

Route::get('/actors', [ActorController::class, 'show'])->name('actor.show');

Route::post('favorite-add/{id}', [WatchlistController::class, 'favoriteAdd'])->name('favorite.add');
Route::delete('favorite-remove/{id}', [WatchlistController::class, 'favoriteRemove'])->name('favorite.remove');

Route::get('watchlist', [WatchlistController::class, 'watchlist'])->name('watchlist');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/reviews', [ReviewController::class, 'create'])->name('reviews.store');

require __DIR__.'/auth.php';
