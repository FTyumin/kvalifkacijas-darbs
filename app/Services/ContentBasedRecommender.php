<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContentBasedRecommender
{
    function findSimilarMovies($movieId, $limit = 5) {
        $targetFeatures = $this->getMovieFeatures($movieId);
    
        $allMovies = Movie::where('id', '!=', $movieId)->get();
        $similarities = [];
        
        foreach($allMovies as $movie) {
            // dd($movie->id);
            $movieFeatures = $this->getMovieFeatures($movie->id);
            $similarity = $this->calculateCosineSimilarity($targetFeatures, $movieFeatures);
    
            if($similarity > 0.1) {
                $similarities[] = [
                    'id' => $movie->id,
                    'value' => $similarity,
                    'name'  => $movie->name,
                ];
            }
        }
        usort($similarities, function($a, $b) {
            return $b['value'] <=> $a['value'];
        });
    
        return array_slice($similarities, 0, $limit);
    }

    function getMovieFeatures($movieId) {
        $movie = Movie::with(['genres', 'director', 'actors'])->find($movieId);
        $features = [];

        $allGenres = Genre::all();

        foreach ($allGenres as $genre) {
            $key = 'genre_' . $genre->id;
            $features[$key] = $movie->genres->contains($genre->id) ? 1 : 0;
        }

        $allDirectors = Person::where('type', 'director')->get(30);
        foreach ($allDirectors as $director) {
            $key = 'director_' . $director->id;
            // dd($movie->director->id);
            $features[$key] = (int) ($movie->director?->id === $director->id);
        }

        $topActors = Person::whereIn('id', function($query) {
            $query->select('person_id')
                ->from('actor_movie')
                ->groupBy('actor_id')
                ->orderByRaw('COUNT(*) DESC');
                // ->limit(100);
        })->get();

        foreach ($topActors as $actor) {
            $key = 'actor_' . $actor->id;
            $features[$key] = $movie->actors->contains($actor->id) ? 1 : 0;
        }

        return $features;
    }

    function calculateCosineSimilarity($features1, $features2) {
        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;

        $allKeys = array_unique(array_merge(array_keys($features1), array_keys($features2)));

        foreach ($allKeys as $key) {
            $val1 = $features1[$key] ?? 0;
            $val2 = $features2[$key] ?? 0;

            $dotProduct += $val1 *  $val2;
            $magnitude1 += $val1 * $val1;
            $magnitude2 += $val2 * $val2; 
        }

        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);

        if($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
        // Formula = (A · B) / (|A| × |B|)
    }




    function getRecommendationsForUser($userId, $limit) {
        $user = User::find($userId);
    
        // retrieve user's favorite genres
        $favoriteGenreIds = $user->favoriteGenres->pluck('id')->toArray();
        $favoriteActorIds = $user->favoriteActors->pluck('id')->toArray();
        $favoriteDirectorIds = $user->favoriteDirectors->pluck('id')->toArray();

        //movies that user shouldn't get as recommendations
        $watchedIds = $user->watchedMovies()->pluck('film_id')->toArray();
        $watchlistIds = $user->watchlist()->pluck('film_id')->toArray();
        $excludeIds = array_merge($watchedIds, $watchlistIds);
        
        // If no favorites, fall back to popular movies
        if (empty($favoriteGenres) && empty($favoriteDirectors)) {
            return $this->getPopularMovies($limit);
        }
    
        // Find movies matching user's taste
        // $recommendations = Movie::query()
        //     ->whereHas('genres', function($q) use ($favoriteGenres) {
        //         $q->whereIn('genres.id', $favoriteGenres);
        //     })
        //     ->orWhereHas('directors', function($q) use ($favoriteDirectors) {
        //         $q->whereIn('directors.id', $favoriteDirectors);
        //     })
        //     ->withAvg('ratings', 'rating')
        //     ->orderByDesc('ratings_avg_rating')
        //     ->limit($limit)
        //     ->get();

            
        // return $recommendations;
    }

    function getPopularMovies($limit) {
        $popularMovies = Movie::select('movies.id', 'movies.name', 'movies.rating')
            ->leftJoin('reviews', 'movies.id', '=', 'reviews.movie_id')
            ->groupBy('movies.id', 'movies.name', 'movies.rating')
            ->orderByRaw('AVG(reviews.rating) DESC')
            ->orderByRaw('COUNT(reviews.id) DESC')
            ->limit($limit)
            ->with(['genres'])
            ->get();
        
        return $popularMovies;
    }



}