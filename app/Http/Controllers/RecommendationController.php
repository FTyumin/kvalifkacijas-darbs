<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use App\Services\ContentBasedRecommender;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    protected ContentBasedRecommender $contentRecommender;


    public function __construct(
        ContentBasedRecommender $contentRecommender,
    ) {
        $this->contentRecommender = $contentRecommender;
    }

    /**
     * Get personalized recommendations for the authenticated user
     */
    public function personalRecommendations(Request $request): JsonResponse
    {
        $user = $request->user();
        $limit = $request->get('limit', 10);

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // if ($userRatingsCount >= 5) {
        //     // Use collaborative filtering for users with enough data
        //     $recommendations = $this->collaborativeRecommender
        //         ->getRecommendationsForUser($user, $limit);
        //     $method = 'collaborative';
        // } else {
            // Use content-based for new users
            $recommendations = $this->contentRecommender
                ->getRecommendationsForUser($user, $limit);
            $method = 'content-based';
        // }

        return response()->json([
            'recommendations' => $recommendations->values(),
            'method' => $method,
            'user_ratings_count' => $userRatingsCount
        ]);
    }

    /**
     * Get movies similar to a specific movie
     */
    public function similarMovies(Movie $movie, Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);

        // Get content-based similar movies
        $contentSimilar = $this->contentRecommender
            ->findSimilarMovies($movie->id, $limit);


        return response()->json([
            'movie' => $movie->load(['genres', 'director', 'actors']),
            'content_similar' => $contentSimilar,
        ]);
    }

    /**
     * Get trending movies
     */
    public function trending(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);



        return response()->json([
            'trending' => $trendingMovies->values()
        ]);
    }

    /**
     * Get recommendations for homepage
     */
    public function homepage(Request $request): JsonResponse
    {
        $user = $request->user();
        $limit = 6; // Smaller limit for homepage

        $response = [];

        if ($user && $user->reviews()->count() >= 3) {
            // Personalized recommendations for logged-in users
            $userRatingsCount = $user->ratings()->count();
            
            
                $personalRecs = $this->contentRecommender
                    ->getRecommendationsForUser($user, $limit);
    

            $response['personal_recommendations'] = $personalRecs->values();
        }


        // Popular movies as fallback
        $popularMovies = Movie::select('movies.id', 'movies.name', 'movies.rating')
            ->leftJoin('reviews', 'movies.id', '=', 'reviews.movie_id')
            ->groupBy('movies.id', 'movies.name', 'movies.rating')
            ->orderByRaw('AVG(reviews.rating) DESC')
            ->orderByRaw('COUNT(reviews.id) DESC')
            ->limit($limit)
            ->with(['genres'])
            ->get();

        $response['popular'] = $popularMovies->map(function ($movie) {
            return [
                'movie' => $movie,
                'average_rating' => $movie->average_rating,
                'ratings_count' => $movie->ratings_count
            ];
        });

        return response()->json($response);
    }

    /**
     * Get recommendations by genre
     */
    public function byGenre(Request $request): JsonResponse
    {
        $genreId = $request->get('genre_id');
        $limit = $request->get('limit', 20);

        if (!$genreId) {
            return response()->json(['error' => 'Genre ID is required'], 400);
        }

        $movies = Movie::byGenre($genreId)
            ->with(['genres', 'directors'])
            ->highlyRated(3.5)
            ->limit($limit)
            ->get();

        return response()->json([
            'movies' => $movies,
            'genre_id' => $genreId
        ]);
    }

    /**
     * Get recommendations based on content
     */
    public function hybridRecommendations(Request $request): JsonResponse
    {
        $user = $request->user();
        $limit = $request->get('limit', 10);

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $contentRecs = $this->contentRecommender
            ->getRecommendationsForUser($user, $limit * 2);


        // Combine and score recommendations
        $hybridScores = [];
        $moviesSeen = [];

        // Process content-based recommendations
        foreach ($contentRecs as $rec) {
            $movieId = $rec['movie']->id;
            if (!isset($moviesSeen[$movieId])) {
                $hybridScores[$movieId] = [
                    'movie' => $rec['movie'],
                    'content_score' => $rec['similarity_score'] ?? 0,
                    // 'collab_score' => 0,
                    'hybrid_score' => 0
                ];
                $moviesSeen[$movieId] = true;
            }
        }


        // Calculate hybrid scores (weighted combination)
        $contentWeight = 0.4;

        foreach ($hybridScores as $movieId => &$scores) {
            $scores['hybrid_score'] = ($contentWeight * $scores['content_score']);
        }

        // Sort by hybrid score and take top results
        // $finalRecs = collect($hybridScores)
        //     ->sortByDesc('hybrid_score')
        //     ->take($limit)
        //     ->values();

        return response()->json([
            'recommendations' => $finalRecs,
            'method' => 'hybrid',
            'weights' => [
                'content' => $contentWeight,
            ]
        ]);
    }

    /**
     * Rate a movie and get instant recommendations
     */
    public function rateAndRecommend(Request $request): JsonResponse
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'rating' => 'required|numeric|min:1|max:5'
        ]);

        $user = $request->user();

        // Save or update rating
        $user->reviews()->updateOrCreate(
            ['movie_id' => $request->movie_id],
            ['rating' => $request->rating]
        );

        // Get fresh recommendations based on the new rating
        $recommendations = $this->contentRecommender
            ->getRecommendationsForUser($user, 5);

        return response()->json([
            'message' => 'Rating saved successfully',
            'fresh_recommendations' => $recommendations->values()
        ]);
    }

    /**
     * Search movies with recommendation scoring
     */
    public function searchWithRecommendations(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $user = $request->user();

        if (!$query) {
            return response()->json(['error' => 'Search query is required'], 400);
        }

        // Search movies
        $movies = Movie::where('title', 'LIKE', "%{$query}%")
            ->with(['genres', 'directors'])
            ->limit(20)
            ->get();

        // If user is logged in, add recommendation scores
        if ($user && $user->reviews()->count() >= 3) {
            $userProfile = $this->buildUserProfile($user);
            
            $movies = $movies->map(function ($movie) use ($userProfile) {
                $movie->recommendation_score = $this->calculateMovieScore($movie, $userProfile);
                return $movie;
            })->sortByDesc('recommendation_score');
        }

        return response()->json([
            'query' => $query,
            'movies' => $movies->values(),
            'has_recommendation_scores' => $user && $user->ratings()->count() >= 3
        ]);
    }

    /**
     * Build simple user profile for scoring
     */
    private function buildUserProfile(User $user): array
    {
        $favoriteGenres = $user->favoriteMovies()
            ->with('genres')
            ->get()
            ->pluck('genres')
            ->flatten()
            ->countBy('id')
            ->toArray();

        return [
            'favorite_genres' => $favoriteGenres,
            'total_favorites' => $user->favoriteMovies()->count()
        ];
    }

    /**
     * Calculate simple recommendation score for search results
     */
    private function calculateMovieScore(Movie $movie, array $userProfile): float
    {
        $score = 0;
        $totalFavorites = $userProfile['total_favorites'];

        if ($totalFavorites > 0) {
            foreach ($movie->genres as $genre) {
                if (isset($userProfile['favorite_genres'][$genre->id])) {
                    $score += $userProfile['favorite_genres'][$genre->id] / $totalFavorites;
                }
            }
        }

        // Add base popularity score
        $score += min($movie->average_rating / 5, 1.0) * 0.3;

        return $score;
    }
}