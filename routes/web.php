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


Route::get('/', [MovieController::class, 'home'])->name('home');

Route::get('admin', [AdminController::class, 'dashboard'])->middleware([Admin::class]);
Route::get('movies/add', [MovieController::class, 'add'])->name('movies.add')->middleware(Admin::class);
Route::post('movies/store', [MovieController::class, 'store'])->name('movies.store')->middleware(Admin::class);

Route::resource('movies', MovieController::class)->only(['index', 'show']);
Route::get('suggestion', [MovieController::class, 'sendSuggestion'])->middleware('auth');
Route::post('suggestions/store', [MovieController::class, 'storeSuggestion'])->name('suggestions.store');

Route::post('/suggestions/{suggestion}/approve', [AdminController::class, 'approveSuggestion'])->name('suggestions.approve');

Route::get('/actors/search', [PeopleController::class, 'search'])->name('actors.search');
Route::get('/directors/search', [PeopleController::class, 'directorSearch'])->name('directors.search');
Route::resource('people', PeopleController::class)->only(['index', 'show']);
Route::resource('genres', GenreController::class)->only(['index', 'show']);

Route::post('/favorite/toggle/{movie}', [MarkController::class, 'favoriteToggle'])->name('favorite.toggle');
Route::post('/watchlist/toggle/{movie}', [MarkController::class, 'watchlistToggle'])->name('watchlist.toggle');
Route::post('/seen/toggle/{movie}', [MarkController::class, 'seenToggle'])->name('seen.toggle');

// profile functions
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

Route::get('users/{userId}/followers', [UserRelationshipController::class, 'followers']);
Route::get('users/{userId}/followees', [UserRelationshipController::class, 'followees']);

Route::delete('api/users/{userId}/unfollow', [UserRelationshipController::class, 'unfollow']);
Route::post('api/users/{userId}/follow', [UserRelationshipController::class, 'follow']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/quiz', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz', [QuizController::class, 'store'])->name('quiz.store');

    Route::post('/people/{person}', [MarkController::class, 'favoritePersonToggle'])->name('person.favorite');
    // Route::post('/people/{person}', [MarkController::class, 'favoriteDirectorToggle'])->name('director.favorite');

    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
});

Route::post('lists/{movie}/add', [ListController::class, 'add'])->name('lists.add');
Route::delete('/lists/{list}/movies/{movie}', [ListController::class, 'remove'])->name('lists.remove');

Route::resource('lists', ListController::class)->only(['index', 'show', 'create', 'store']);
// Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'like']);
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
Route::post('review/{review}/like', [ReviewController::class, 'toggleLike'])->name('reviews.like');


Route::post('/comments', [CommentController::class, 'create'])->name('comments.store');

Route::get('/search', [MovieController::class, 'search'])->name('movies.search');

require __DIR__.'/auth.php';
