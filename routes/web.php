<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserRelationshipController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::resource('movies', MovieController::class)->only(['index', 'show']);
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
});

Route::post('lists/{movie}/add', [ListController::class, 'add'])->name('lists.add');
Route::delete('lists/{movie}/remove', [ListController::class, 'remove'])->name('lists.remove');

Route::resource('lists', ListController::class)->only(['index', 'show', 'create', 'store']);
// Public routes
Route::prefix('recommendations')->group(function () {
    
    // Homepage recommendations
    Route::get('/homepage', [RecommendationController::class, 'homepage'])
        ->name('recommendations.homepage');
    
    // Movies similar to a specific movie
    Route::get('/movies/{movie}/similar', [RecommendationController::class, 'SimilarMovies'])
        ->name('recommendations.similar');
    
    // Trending movies
    Route::get('/trending', [RecommendationController::class, 'trending'])
        ->name('recommendations.trending');
    
    // Recommendations by genre
    Route::get('/genre', [RecommendationController::class, 'byGenre'])
        ->name('recommendations.genre');
});

Route::get('/recommendations', [MovieController::class, 'recommendations'])->name('movies.recommendations');
Route::get('/top-movies', [MovieController::class, 'topPage'])->name('movies.top');

Route::post('/reviews', [ReviewController::class, 'create'])->name('reviews.store');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

Route::post('/comments', [CommentController::class, 'create'])->name('comments.store');

Route::get('/search', [MovieController::class, 'search'])->name('movies.search');

require __DIR__.'/auth.php';
