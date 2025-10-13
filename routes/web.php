<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('movies', MovieController::class)->only(['index', 'show']);
Route::resource('directors', DirectorController::class)->only(['index','show']);
Route::resource('genres', GenreController::class)->only(['index', 'show']);

Route::get('/actors', [ActorController::class, 'show'])->name('actor.show');

Route::post('favorite-add/{id}', [WatchlistController::class, 'favoriteAdd'])->name('favorite.add');
Route::delete('favorite-remove/{id}', [WatchlistController::class, 'favoriteRemove'])->name('favorite.remove');

Route::post('bookmark-add/{id}', [BookmarkController::class, 'bookmarkAdd'])->name('bookmark.add');
Route::delete('bookmark-remove/{id}', [BookmarkController::class, 'bookmarkRemove'])->name('bookmark.remove');


Route::get('watchlist', [WatchlistController::class, 'watchlist'])->name('watchlist');
Route::get('bookmark', [BookmarkController::class, 'bookmarkList'])->name('bookmark');

// profila fjas
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

Route::get('profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

// rekomendacijas
// API Routes for recommendations
// Route::middleware('auth:sanctum')->group(function () {
    
    // Personal recommendations
    Route::get('/recommendations/personal', [RecommendationController::class, 'personalRecommendations'])
        ->name('recommendations.personal');
    
    // Hybrid recommendations
    Route::get('/recommendations/hybrid', [RecommendationController::class, 'hybridRecommendations'])
        ->name('recommendations.hybrid');
    
    // Rate a movie and get recommendations
    Route::post('/movies/{movie}/rate', [RecommendationController::class, 'rateAndRecommend'])
        ->name('movies.rate');
    
    // Search with recommendations
    Route::get('/search/recommendations', [RecommendationController::class, 'searchWithRecommendations'])
        ->name('search.recommendations');
// });

// Public routes
Route::prefix('recommendations')->group(function () {
    
    // Homepage recommendations
    Route::get('/homepage', [RecommendationController::class, 'homepage'])
        ->name('recommendations.homepage');
    
    // Movies similar to a specific movie
    Route::get('/movies/{movie}/similar', [RecommendationController::class, 'similarMovies'])
        ->name('recommendations.similar');
    
    // Trending movies
    Route::get('/trending', [RecommendationController::class, 'trending'])
        ->name('recommendations.trending');
    
    // Recommendations by genre
    Route::get('/genre', [RecommendationController::class, 'byGenre'])
        ->name('recommendations.genre');
});

Route::get('/recommendations', [MovieController::class, 'recommendations'])->name('movies.recommendations');

Route::post('/reviews', [ReviewController::class, 'create'])->name('reviews.store');

Route::get('/search', [MovieController::class, 'search'])->name('movies.search');


require __DIR__.'/auth.php';
