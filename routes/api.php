<?php

use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;


// API Routes for recommendations
// Route::middleware('auth:sanctum')->group(function () {
    
    // Personal recommendations
    // Route::get('/recommendations/personal', [RecommendationController::class, 'personalRecommendations'])
    //     ->name('recommendations.personal');
    
    // // Hybrid recommendations
    // Route::get('/recommendations/hybrid', [RecommendationController::class, 'hybridRecommendations'])
    //     ->name('recommendations.hybrid');
    
    // // Rate a movie and get recommendations
    // Route::post('/movies/{movie}/rate', [RecommendationController::class, 'rateAndRecommend'])
    //     ->name('movies.rate');
    
    // // Search with recommendations
    // Route::get('/search/recommendations', [RecommendationController::class, 'searchWithRecommendations'])
    //     ->name('search.recommendations');

    // Route::get('/recommendations/{movie}', [RecommendationController::class, 'findSimilarMovies'])
    //     ->name('recommendations.movie');
// });

require __DIR__.'/auth.php';