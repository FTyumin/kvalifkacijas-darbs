<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserRelationshipController;
use App\Http\Controllers\FeedController;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::get('/load', [AdminController::class, 'load'])->name('movies.load');
    Route::post('/load', [AdminController::class, 'loadMovies'])->name('movies.load.store');
    Route::resource('movies', MovieController::class)->except(['index', 'show']);
    Route::post('/suggestions/{suggestion}/approve', [AdminController::class, 'approveSuggestion'])->name('suggestions.approve');
    Route::post('/suggestions/{suggestion}/reject', [AdminController::class, 'rejectSuggestion'])->name('suggestions.reject');
});
Route::get('/', [MovieController::class, 'home'])->name('home');

Route::resource('movies', MovieController::class)->only(['index', 'show']);

Route::get('/actors/search', [PeopleController::class, 'search'])->name('actors.search');
Route::get('/directors/search', [PeopleController::class, 'directorSearch'])->name('directors.search');
Route::resource('people', PeopleController::class)->only(['index', 'show']);
// Route::resource('genres', GenreController::class)->only(['index', 'show']);

Route::get('genres/{genre}', [GenreController::class, 'show'])->name('genres.show');

// profile functions
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

Route::get('users/{userId}/followers', [UserRelationshipController::class, 'followers']);
Route::get('users/{userId}/followees', [UserRelationshipController::class, 'followees']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/quiz', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz', [QuizController::class, 'store'])->name('quiz.store');

    Route::post('/people/{person}', [MarkController::class, 'favoritePersonToggle'])->name('person.favorite');

    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');

    // follow/unfollow
    Route::delete('api/users/{userId}/unfollow', [UserRelationshipController::class, 'unfollow']);
    Route::post('api/users/{userId}/follow', [UserRelationshipController::class, 'follow']);

    // marking functions
    Route::post('/favorite/toggle/{movie}', [MarkController::class, 'favoriteToggle'])->name('favorite.toggle');
    Route::post('/watchlist/toggle/{movie}', [MarkController::class, 'watchlistToggle'])->name('watchlist.toggle');
    Route::post('/seen/toggle/{movie}', [MarkController::class, 'seenToggle'])->name('seen.toggle');

    Route::get('suggestion', [MovieController::class, 'sendSuggestion']);
    Route::post('suggestions/store', [MovieController::class, 'storeSuggestion'])->name('suggestions.store');

    Route::post('lists/{movie}/add', [ListController::class, 'add'])->name('lists.add');
    Route::delete('/lists/{list}/movies/{movie}', [ListController::class, 'remove'])->name('lists.remove');

    Route::post('review/{review}/like', [ReviewController::class, 'toggleLike'])->name('reviews.like');

    Route::post('/comments', [CommentController::class, 'create'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});



Route::resource('lists', ListController::class)->only(['index', 'show', 'create', 'store']);
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');


Route::get('/search', [MovieController::class, 'search'])->name('movies.search');

require __DIR__.'/auth.php';
