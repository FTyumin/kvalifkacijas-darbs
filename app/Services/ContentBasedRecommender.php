<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContentBasedRecommender
{
    /**
     * Get movie recommendations based on content similarity
     */
    public function getRecommendationsForMovie(int $movieId, int $limit = 10): Collection
    {
        $targetMovie = Movie::with(['genres', 'directors', 'actors'])->find($movieId);
        
        if (!$targetMovie) {
            return collect();
        }

        // Get all other movies with their metadata
        $allMovies = Movie::with(['genres', 'directors', 'actors'])
            ->where('id', '!=', $movieId)
            ->get();

        $recommendations = collect();

        foreach ($allMovies as $movie) {
            $similarity = $this->calculateContentSimilarity($targetMovie, $movie);
            
            if ($similarity > 0.1) { // Minimum threshold
                $recommendations->push([
                    'movie' => $movie,
                    'similarity_score' => $similarity
                ]);
            }
        }

        return $recommendations
            ->sortByDesc('similarity_score')
            ->take($limit);
    }

    /**
     * Get recommendations for a user based on their liked movies
     */
    public function getRecommendationsForUser(User $user, int $limit = 10): Collection
    {
        // Get user's highly rated movies (rating >= 4)
        $likedMovies = $user->reviews()
            ->with(['movie.genres', 'movie.directors', 'movie.actors'])
            ->where('rating', '>=', 4)
            ->get()
            ->pluck('movie');

        if ($likedMovies->isEmpty()) {
            return $this->getPopularMovies($limit);
        }

        // Create user profile based on liked movies
        $userProfile = $this->createUserProfile($likedMovies);

        // Get movies user hasn't rated
        $ratedMovieIds = $user->reviews()->pluck('movie_id')->toArray();
        $candidateMovies = Movie::with(['genres', 'directors', 'actors'])
            ->whereNotIn('id', $ratedMovieIds)
            ->get();

        $recommendations = collect();

        foreach ($candidateMovies as $movie) {
            $similarity = $this->calculateProfileSimilarity($userProfile, $movie);
            
            if ($similarity > 0.1) {
                $recommendations->push([
                    'movie' => $movie,
                    'similarity_score' => $similarity
                ]);
            }
        }

        return $recommendations
            ->sortByDesc('similarity_score')
            ->take($limit);
    }

    /**
     * Calculate content similarity between two movies
     */
    private function calculateContentSimilarity(Movie $movie1, Movie $movie2): float
    {
        $genreSimilarity = $this->calculateGenreSimilarity($movie1, $movie2);
        $directorSimilarity = $this->calculateDirectorSimilarity($movie1, $movie2);
        $actorSimilarity = $this->calculateActorSimilarity($movie1, $movie2);

        // Weighted combination
        return (0.4 * $genreSimilarity) + (0.3 * $directorSimilarity) + (0.3 * $actorSimilarity);
    }

    /**
     * Calculate genre similarity using Jaccard coefficient
     */
    private function calculateGenreSimilarity(Movie $movie1, Movie $movie2): float
    {
        $genres1 = $movie1->genres->pluck('id')->toArray();
        $genres2 = $movie2->genres->pluck('id')->toArray();

        return $this->jaccardSimilarity($genres1, $genres2);
    }

    /**
     * Calculate director similarity
     */
    private function calculateDirectorSimilarity(Movie $movie1, Movie $movie2): float
    {
        $directors1 = $movie1->directors->pluck('id')->toArray();
        $directors2 = $movie2->directors->pluck('id')->toArray();

        return $this->jaccardSimilarity($directors1, $directors2);
    }

    /**
     * Calculate actor similarity (top actors only for performance)
     */
    private function calculateActorSimilarity(Movie $movie1, Movie $movie2): float
    {
        // Get top 5 actors for each movie to avoid performance issues
        $actors1 = $movie1->actors->take(5)->pluck('id')->toArray();
        $actors2 = $movie2->actors->take(5)->pluck('id')->toArray();

        return $this->jaccardSimilarity($actors1, $actors2);
    }

    /**
     * Calculate Jaccard similarity coefficient
     */
    private function jaccardSimilarity(array $set1, array $set2): float
    {
        if (empty($set1) && empty($set2)) {
            return 0;
        }

        $intersection = count(array_intersect($set1, $set2));
        $union = count(array_unique(array_merge($set1, $set2)));

        return $union > 0 ? $intersection / $union : 0;
    }

    /**
     * Create user profile from liked movies
     */
    private function createUserProfile(Collection $likedMovies): array
    {
        $genreFreq = [];
        $directorFreq = [];
        $actorFreq = [];

        foreach ($likedMovies as $movie) {
            // Count genres
            foreach ($movie->genres as $genre) {
                $genreFreq[$genre->id] = ($genreFreq[$genre->id] ?? 0) + 1;
            }

            // Count directors
            foreach ($movie->directors as $director) {
                $directorFreq[$director->id] = ($directorFreq[$director->id] ?? 0) + 1;
            }

            // Count actors (top 3 per movie)
            foreach ($movie->actors->take(3) as $actor) {
                $actorFreq[$actor->id] = ($actorFreq[$actor->id] ?? 0) + 1;
            }
        }

        return [
            'genres' => $genreFreq,
            'directors' => $directorFreq,
            'actors' => $actorFreq,
            'total_movies' => $likedMovies->count()
        ];
    }

    /**
     * Calculate similarity between user profile and a movie
     */
    private function calculateProfileSimilarity(array $userProfile, Movie $movie): float
    {
        $genreScore = $this->calculateProfileGenreScore($userProfile, $movie);
        $directorScore = $this->calculateProfileDirectorScore($userProfile, $movie);
        $actorScore = $this->calculateProfileActorScore($userProfile, $movie);

        // Weighted combination
        return (0.4 * $genreScore) + (0.3 * $directorScore) + (0.3 * $actorScore);
    }

    /**
     * Calculate genre score for user profile
     */
    private function calculateProfileGenreScore(array $userProfile, Movie $movie): float
    {
        $score = 0;
        $totalMovies = $userProfile['total_movies'];

        foreach ($movie->genres as $genre) {
            if (isset($userProfile['genres'][$genre->id])) {
                $score += $userProfile['genres'][$genre->id] / $totalMovies;
            }
        }

        return min($score, 1.0); // Cap at 1.0
    }

    /**
     * Calculate director score for user profile
     */
    private function calculateProfileDirectorScore(array $userProfile, Movie $movie): float
    {
        $score = 0;
        $totalMovies = $userProfile['total_movies'];

        foreach ($movie->directors as $director) {
            if (isset($userProfile['directors'][$director->id])) {
                $score += $userProfile['directors'][$director->id] / $totalMovies;
            }
        }

        return min($score, 1.0);
    }

    /**
     * Calculate actor score for user profile
     */
    private function calculateProfileActorScore(array $userProfile, Movie $movie): float
    {
        $score = 0;
        $totalMovies = $userProfile['total_movies'];

        foreach ($movie->actors->take(3) as $actor) {
            if (isset($userProfile['actors'][$actor->id])) {
                $score += $userProfile['actors'][$actor->id] / $totalMovies;
            }
        }

        return min($score, 1.0);
    }

    /**
     * Fallback: Get popular movies
     */
    private function getPopularMovies(int $limit): Collection
    {
        $popularMovies = Movie::select('movies.id')
            ->leftJoin('reviews', 'movies.id', '=', 'reviews.movie_id')
            ->groupBy('movies.id')
            ->orderByRaw('AVG(reviews.rating) DESC')
            ->orderByRaw('COUNT(reviews.id) DESC')
            ->limit($limit)
            ->get();

        return $popularMovies->map(function ($movie) {
            return [
                'movie' => $movie,
                'similarity_score' => 0.5 // Default score for popular movies
            ];
        });
    }
}