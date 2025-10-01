<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CollaborativeFilteringRecommender
{
    /**
     * Get movie recommendations for a user based on similar users' preferences
     */
    public function getRecommendationsForUser(User $user, int $limit = 10): Collection
    {
        // Get similar users
        $similarUsers = $this->findSimilarUsers($user, 50);
        
        if ($similarUsers->isEmpty()) {
            return $this->getPopularMovies($limit);
        }

        // Get movies the user hasn't rated
        $userRatedMovies = $user->reviews()->pluck('movie_id')->toArray();
        
        // Get recommendations from similar users
        $movieScores = $this->calculateMovieScores($similarUsers, $userRatedMovies);

        // Sort and limit results
        $recommendations = collect($movieScores)
            ->sortByDesc('predicted_rating')
            ->take($limit)
            ->map(function ($item) {
                return [
                    'movie' => Movie::find($item['movie_id']),
                    'predicted_rating' => $item['predicted_rating'],
                    'confidence' => $item['confidence']
                ];
            });

        return $recommendations;
    }

    /**
     * Get movies that users who liked this movie also liked
     */
    public function getMoviesLikedByFans(int $movieId, int $limit = 10): Collection
    {
        // Get users who rated this movie highly (>= 4)
        $fans = Rating::where('movie_id', $movieId)
            ->where('rating', '>=', 4)
            ->pluck('user_id');

        if ($fans->isEmpty()) {
            return collect();
        }

        // Get movies these fans also rated highly
        $fanMovies = Rating::whereIn('user_id', $fans)
            ->where('movie_id', '!=', $movieId)
            ->where('rating', '>=', 4)
            ->with('movie')
            ->get()
            ->groupBy('movie_id')
            ->map(function ($ratings) {
                return [
                    'movie' => $ratings->first()->movie,
                    'avg_rating' => $ratings->avg('rating'),
                    'fan_count' => $ratings->count(),
                    'score' => $ratings->avg('rating') * log($ratings->count() + 1) // TF-IDF inspired scoring
                ];
            })
            ->sortByDesc('score')
            ->take($limit);

        return $fanMovies;
    }

    /**
     * Find users similar to the given user
     */
    private function findSimilarUsers(User $user, int $limit = 50): Collection
    {
        $cacheKey = "similar_users_{$user->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($user, $limit) {
            // Get user's ratings
            $userRatings = $user->reviews()
                ->select('movie_id', 'rating')
                ->get()
                ->pluck('rating', 'movie_id');

            if ($userRatings->isEmpty()) {
                return collect();
            }

            // Find users who rated at least 3 of the same movies
            $commonMovies = $userRatings->keys()->toArray();
            $similarUsers = collect();

            $otherUsers = User::whereHas('reviews', function ($query) use ($commonMovies) {
                    $query->whereIn('movie_id', $commonMovies);
                })
                ->where('id', '!=', $user->id)
                ->with(['ratings' => function ($query) use ($commonMovies) {
                    $query->whereIn('movie_id', $commonMovies);
                }])
                ->get();

            foreach ($otherUsers as $otherUser) {
                $otherRatings = $otherUser->ratings->pluck('rating', 'movie_id');
                
                // Need at least 3 movies in common
                $commonRatedMovies = array_intersect_key(
                    $userRatings->toArray(), 
                    $otherRatings->toArray()
                );

                if (count($commonRatedMovies) >= 3) {
                    $similarity = $this->calculatePearsonCorrelation(
                        $userRatings->toArray(),
                        $otherRatings->toArray()
                    );

                    if ($similarity > 0.1) { // Minimum similarity threshold
                        $similarUsers->push([
                            'user' => $otherUser,
                            'similarity' => $similarity,
                            'common_movies' => count($commonRatedMovies)
                        ]);
                    }
                }
            }

            return $similarUsers->sortByDesc('similarity')->take($limit);
        });
    }

    /**
     * Calculate Pearson correlation coefficient between two users
     */
    private function calculatePearsonCorrelation(array $ratings1, array $ratings2): float
    {
        $commonMovies = array_intersect_key($ratings1, $ratings2);
        
        if (count($commonMovies) < 2) {
            return 0;
        }

        $sum1 = $sum2 = $sum1Sq = $sum2Sq = $pSum = 0;
        $n = count($commonMovies);

        foreach ($commonMovies as $movieId => $rating1) {
            $rating2 = $ratings2[$movieId];
            
            $sum1 += $rating1;
            $sum2 += $rating2;
            $sum1Sq += $rating1 * $rating1;
            $sum2Sq += $rating2 * $rating2;
            $pSum += $rating1 * $rating2;
        }

        $numerator = $pSum - ($sum1 * $sum2 / $n);
        $denominator = sqrt(
            ($sum1Sq - ($sum1 * $sum1) / $n) * 
            ($sum2Sq - ($sum2 * $sum2) / $n)
        );

        return $denominator != 0 ? $numerator / $denominator : 0;
    }

    /**
     * Calculate predicted ratings for movies based on similar users
     */
    private function calculateMovieScores(Collection $similarUsers, array $excludeMovies): array
    {
        $movieScores = [];

        // Get all movies rated by similar users
        $userIds = $similarUsers->pluck('user.id')->toArray();
        $userSimilarities = $similarUsers->pluck('similarity', 'user.id')->toArray();

        $candidateRatings = Rating::whereIn('user_id', $userIds)
            ->whereNotIn('movie_id', $excludeMovies)
            ->get()
            ->groupBy('movie_id');

        foreach ($candidateRatings as $movieId => $ratings) {
            $weightedSum = 0;
            $similaritySum = 0;
            $raterCount = 0;

            foreach ($ratings as $rating) {
                $similarity = $userSimilarities[$rating->user_id] ?? 0;
                if ($similarity > 0) {
                    $weightedSum += $rating->rating * $similarity;
                    $similaritySum += abs($similarity);
                    $raterCount++;
                }
            }

            if ($similaritySum > 0 && $raterCount >= 2) {
                $predictedRating = $weightedSum / $similaritySum;
                
                // Confidence based on number of raters and similarity sum
                $confidence = min(1.0, ($raterCount * $similaritySum) / 10);

                $movieScores[] = [
                    'movie_id' => $movieId,
                    'predicted_rating' => $predictedRating,
                    'confidence' => $confidence,
                    'rater_count' => $raterCount
                ];
            }
        }

        return $movieScores;
    }

    /**
     * Get popular movies as fallback
     */
    private function getPopularMovies(int $limit): Collection
    {
        return Cache::remember('popular_movies', 3600, function () use ($limit) {
            $popularMovies = Movie::select('movies.*')
                ->leftJoin('ratings', 'movies.id', '=', 'ratings.movie_id')
                ->groupBy('movies.id')
                ->having(DB::raw('COUNT(ratings.id)'), '>=', 10) // At least 10 ratings
                ->orderByRaw('AVG(ratings.rating) DESC')
                ->orderByRaw('COUNT(ratings.id) DESC')
                ->limit($limit)
                ->get();

            return $popularMovies->map(function ($movie) {
                return [
                    'movie' => $movie,
                    'predicted_rating' => 4.0, // Default score
                    'confidence' => 0.5
                ];
            });
        });
    }

    /**
     * Get trending movies (highly rated recent movies)
     */
    public function getTrendingMovies(int $limit = 10): Collection
    {
        return Cache::remember('trending_movies', 1800, function () use ($limit) {
            $thirtyDaysAgo = now()->subDays(30);

            return Movie::select('movies.id')
                ->leftJoin('reviews', 'movies.id', '=', 'reviews.movie_id')
                ->where('reviews.created_at', '>=', $thirtyDaysAgo)
                ->groupBy('movies.id')
                ->having(DB::raw('COUNT(reviews.id)'), '>=', 5) // At least 5 recent ratings
                ->orderByRaw('AVG(reviews.rating) DESC')
                ->orderByRaw('COUNT(reviews.id) DESC')
                ->limit($limit)
                ->get()
                ->map(function ($movie) {
                    return [
                        'movie' => $movie,
                        'predicted_rating' => 4.0,
                        'confidence' => 0.7
                    ];
                });
        });
    }
}