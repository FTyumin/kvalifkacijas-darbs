<?php

namespace App\Services;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ContentBasedRecommender
{
    function findSimilarMovies($movieId, $limit = 5) {    

        $allMovies = Movie::where('id', '!=', $movieId)->get();
        $similarities = [];
        
        foreach($allMovies as $movie) {
            $similarity = $this->calculateMovieSimilarity($movieId, $movie->id);
    
            if($similarity > 0.1) {
                $similarities[] = [
                    'movie' => $movie,
                    'similarity' => $similarity,
                ];
            }
        }
        usort($similarities, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
    
        return array_slice($similarities, 0, $limit);
    }

    function calculateMovieSimilarity($movie1, $movie2) {
        if((!$movie1) or (!$movie2)) {
            return;
        }
        $movie1 = Movie::find($movie1);
        $movie2 = Movie::find($movie2);
        
        // Get genre IDs as arrays
        $genres1 = DB::table('genre_movie')
            ->where('movie_id', $movie1->id)
            ->get('genre_id');
        $genres2 = DB::table('genre_movie')
            ->where('movie_id', $movie2->id)
            ->get('genre_id');
        
        // Get actor IDs as arrays
        $actors1 = $movie1->actors->pluck('id')->toArray();
        $actors2 = $movie2->actors->pluck('id')->toArray();
        
        // Get director IDs as arrays
        $director1 = $movie1->director->pluck('id')->toArray();
        $director2 = $movie2->director->pluck('id')->toArray();

        
        $genres1 = $genres1->pluck('genre_id')->toArray();
        $genres2 = $genres2->pluck('genre_id')->toArray();


        // Calculate Jaccard index for each component
        $genreJaccard = $this->jaccardIndex($genres1, $genres2);
        $actorJaccard = $this->jaccardIndex($actors1, $actors2);
        $directorJaccard = $this->jaccardIndex($director1, $director2);

        // Weighted combination
        $similarity = (0.3 * $genreJaccard) + (0.4 * $directorJaccard) + (0.3 * $actorJaccard); 
        
        return $similarity;
    }

    function jaccardIndex($set1, $set2) {
        if (empty($set1) && empty($set2)) {
            return 0;
        }
        
        // Calculate intersection (items in both sets)
        $intersection = count(array_intersect($set1, $set2));
        
        // Calculate union (all unique items from both sets)
        $union = count(array_unique(array_merge($set1, $set2)));
        
        // Jaccard = |A ∩ B| / |A ∪ B|
        return $union > 0 ? $intersection / $union : 0;
    }

    private function collectSimilarMovies(array $ids, float $weight): array
    {
        $result = [];

        foreach ($ids as $id) {
            foreach ($this->findSimilarMovies($id, 5) as $movie) {
                $movie['similarity'] *= $weight;
                $result[] = $movie;
            }
        }

        return $result;
    }

    public function getPersonMovies(array $ids) : array {
        $result = [];

        foreach($ids as $id) {
            $person = Person::find($id);
            if($person->type == 'actor') {

                $movies = $person->moviesAsActor->toArray();
            } else {
                $movies = $person->moviesAsDirector->toArray();
            }
            $result = array_merge($result, $movies);
        }

        $correctResult = [];

        // build correct format
        foreach($movies as $movie) {
            $correctResult[] = [
                'movie' => $movie,
                'similarity' => 0.2,
            ];
        }
        return $correctResult;
    }

    private function checkUserFavorites(array $recs, User $user) {
        // check if recs have user's favorite actors, directors
        // if yes, increase similarity
        $favoriteActorIds = $user->favoriteActors->pluck('id')->toArray();
        $favoriteDirectorIds = $user->favoriteDirectors->pluck('id')->toArray();

        foreach($recs as $rec) {
            $actors = $rec['movie']->actors->pluck('id')->toArray();

            if(in_array($rec['movie']->director_id, $favoriteDirectorIds)) {
                $rec['similarity'] *= 1.2;
            }
            if(array_intersect($actors, $favoriteActorIds)) {
                $rec['similarity'] *= 1.2;
            }
        }
        return $recs;
    }

    function getRecommendationsForUser($userId, $limit) {
        $user = User::find($userId);
        if(!$user) {
            return;
        }
        
        // retrieve user's favorite genres
        $favoriteGenres = $user->favoriteGenres;
        $favoriteActorIds = $user->favoriteActors->pluck('id')->toArray();
        $favoriteDirectorIds = $user->favoriteDirectors->pluck('id')->toArray();

        //movies that user shouldn't get as recommendations
        $watchedIds = $user->seenMovies()->pluck('markable_id')->toArray();
        $watchlistIds = $user->wantToWatch()->pluck('markable_id')->toArray();
        $favoriteIds = $user->favorites()->pluck('markable_id')->toArray();
        $excludeIds = array_merge($watchedIds, $watchlistIds, $favoriteIds);

        // get user's high ratings(4-5 stars)
        $reviews = $user->reviews()->where('rating', '>=', 4)->get();
        $allRecommendations = [];

        $favoriteSimilar = [];
        $reviewSimilar = [];
        $seenList = [];
        $genreList = [];

        $userHasData = false;

        if (count($favoriteIds) > 0) {
            $userHasData = true;
            $favoriteSimilar = $this->collectSimilarMovies($favoriteIds, 1.4);
        } 

        if ($reviews && $reviews->count() > 0) {
            $userHasData = true;

            $movieIds = $reviews->pluck('movie_id')->toArray();
            $reviewSimilar = $this->collectSimilarMovies($movieIds, 1.3);
        } 

        if (count($watchedIds) > 0) {   
            $userHasData = true;

            $seenList = $this->collectSimilarMovies($watchedIds, 1.05);
        }
        if ($favoriteGenres->count() > 0) {
            $userHasData = true;
            
            $count = $favoriteGenres->count();
            $perGenre = floor($limit / $count);
            
            foreach($favoriteGenres as $genre) {
                $genreList = array_merge($genreList, $this->getGenreMovies($genre, $perGenre));
            }

            foreach($genreList as &$movieData) {
                $movieData['similarity'] *= 1.2;
            }
        } 
        if(!$userHasData) {
            return $this->getRecommendationsForNewUser($user, $limit);
        }

        $allRecommendations = array_merge($favoriteSimilar, $reviewSimilar, $seenList, $genreList);


        $unique = [];
        foreach ($allRecommendations as $rec) {
            $movieId = $rec['movie']->id;

            if (!isset($unique[$movieId])) {
                $unique[$movieId] = $rec;
            }
        }

        $noDuplicates = array_values($unique);

        usort($noDuplicates, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        $result = $noDuplicates;

        if ($excludeIds) {
            $result = array_filter($result, function ($rec) use ($excludeIds) {
                return !in_array($rec['movie']->id, $excludeIds);
            });
        }

        $result = array_slice($result, 0, $limit);
        if (count($result) < $limit) {
            $missing = $limit - count($result);

            $extra = $this->getPopularMovies($missing, $excludeIds);
            $result = array_merge($extra, $result);
        }

        $result = $this->checkUserFavorites($result, $user);

        $result = array_slice($result, 0, $limit);
        return $result;
    }

    private function getRecommendationsForNewUser(User $user, $limit) {
        if (count($user->favoriteGenres) == 0) {
            return $this->getPopularMovies($limit);
        }
        $favoriteGenres = $user->favoriteGenres;
        $count = $favoriteGenres->count();
        $perGenre = floor($limit / $count);
        $recs = [];

        foreach($user->favoriteGenres as $genre) {
            $recs = array_merge($recs, $this->getGenreMovies($genre, $perGenre));
        }

        foreach($recs as &$movieData) {
            $movieData['similarity'] *= 1.2;
        }

        return $recs;
    }

    private function getGenreMovies(Genre $genre, $count) {

        $movies = $genre->movies()->limit($count)->get();

        // If not enough movies, fill from global pool
        if ($movies->count() < $count) {
            $missing = $count - $movies->count();

            $extra = $this->getPopularMovies($missing);

            $movies = $movies->merge($extra);
        }

        // Build result format
        return $movies->map(fn($movie) => [
            'movie' => $movie,
            'similarity' => 0.2,
        ])->toArray();
    }

    function getPopularMovies($limit, $excludeIds = []) {
        $popularMovies = Movie::where('tmdb_rating', '>', 4)->whereNotIn('id', $excludeIds)->limit($limit)->get();
        //exclude seen, favorites
      
        $popularMovies = $popularMovies->map(fn($movie) => [
            'movie' => $movie,
            'similarity' => 0.2,
        ])->toArray();


        return $popularMovies;
    }
}