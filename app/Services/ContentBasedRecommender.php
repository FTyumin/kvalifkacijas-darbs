<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContentBasedRecommender
{
    private $documentFrequency = []; // IDF values
    private $allMoviesKeywords = [];
    private $totalDocuments;

    function findSimilarMovies($movieId, $limit = 5) {
        $targetFeatures = $this->getMovieFeatures($movieId);
    
        $allMovies = Movie::where('id', '!=', $movieId)->get();
        $similarities = [];
        
        foreach($allMovies as $movie) {
            // dd($movie->id);
            $movieFeatures = $this->getMovieFeatures($movie->id);
            $similarity = $this->calculateMovieSimilarity($movieId, $movie->id);
    
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

    function calculateMovieSimilarity($movie1, $movie2) {
        // Get genre IDs as sets
        $genres1 = $movie1->genres->pluck('id')->toArray();
        $genres2 = $movie2->genres->pluck('id')->toArray();
        
        // Get actor IDs as sets
        $actors1 = $movie1->actors->pluck('id')->toArray();
        $actors2 = $movie2->actors->pluck('id')->toArray();
        
        // Get director IDs as sets
        $director1 = $movie1->director ? [$movie1->director->id] : [];
        $director2 = $movie2->director ? [$movie2->director->id] : [];
        
        // Calculate Jaccard index for each component
        $genreJaccard = $this->jaccardIndex($genres1, $genres2);
        $actorJaccard = $this->jaccardIndex($actors1, $actors2);
        $directorJaccard = $this->jaccardIndex($director1, $director2);
        
        // Weighted combination
        // Genre: 40% 
        // Director: 30% 
        // Actors: 30% 
        $similarity = (0.4 * $genreJaccard) + 
                      (0.3 * $directorJaccard) + 
                      (0.3 * $actorJaccard);
        
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

    function calculateDescriptionSimilarity($movie1, $movie2) {
        $text1 = $this->getTfIdfVector($movie1->description);
        $text2 = $this->getTfIdfVector($movie2->description);

        $textSimilarity = $this->calculateCosineSimilarity($text1, $text2);
        return $textSimilarity;
    }

    function getTfIdfVector($description) {
        $keywords = $this->extractKeyWords($description);
        
        if(empty($keywords)) {
            return [];
        }
        $termFrequency = array_count_values($keywords);
        $totalTerms = count($keywords);

        if (empty($this->documentFrequency)) {
            $this->calculateDocumentFrequencies();
        }

        $tfidfVector = [];
        // $totalDocuments = Cache::get('tfidf_movie_count', 0);

        foreach ($termFrequency as $term => $frequency) {
            // Calculate TF (Term Frequency)
            $tf = $frequency / $totalTerms;
            
            $idf = $this->documentFrequency[$term] ?? 0;
            
            // TF-IDF = TF × IDF
            $tfidfVector[$term] = $tf * $idf;
        }
        return $tfidfVector;
    }

    function calculateDocumentFrequencies() {
        // Check cache first
        $cached = Cache::get('tfidf_document_frequencies');
        if ($cached) {
            $this->documentFrequency = $cached;
            return;
        }
        
        // only load needed columns
        $allMovies = Movie::whereNotNull('description')
            ->select('id', 'description')
            ->get();
            
        $totalDocuments = $allMovies->count();
        $this->totalDocuments = $totalDocuments;
        if ($totalDocuments == 0) {
            return;
        }
        
        $termDocumentCount = [];
        
        // Count documents containing each term
         foreach ($allMovies as $movie) {
            if (!$movie->description) {
                continue;
            }
            
            $keywords = $this->extractKeyWords($movie->description);
            $uniqueKeywords = array_unique($keywords);
            
            foreach ($uniqueKeywords as $keyword) {
                if (!isset($termDocumentCount[$keyword])) {
                    $termDocumentCount[$keyword] = 0;
                }
                $termDocumentCount[$keyword]++;
            }
        }
        
        // Calculate IDF for each term
        foreach ($termDocumentCount as $term => $count) {
            if ($count > 0) {  // Safety check
                $this->documentFrequency[$term] = log($totalDocuments / $count);
            }
        }
        
        // Cache with expiration time (24 hours)
        Cache::put('tfidf_document_frequencies', $this->documentFrequency, 86400);
        Cache::put('tfidf_movie_count', $totalDocuments, 86400);
        Cache::put('tfidf_last_calculated', now(), 86400);
    }

    function calculateCosineSimilarity($vector1, $vector2) {
        // Get all unique terms from both vectors
        $allTerms = array_unique(array_merge(array_keys($vector1), array_keys($vector2)));
        
        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;
        
        foreach ($allTerms as $term) {
            $val1 = $vector1[$term] ?? 0;
            $val2 = $vector2[$term] ?? 0;
            
            $dotProduct += $val1 * $val2;
            $magnitude1 += $val1 * $val1;
            $magnitude2 += $val2 * $val2;
        }
        
        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);
        
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }
        
        return round($dotProduct / ($magnitude1 * $magnitude2), 2);
    }

    function extractKeyWords($movieDescription) {
        $text = strtolower($movieDescription);
        $removedPunctuation = preg_replace('/[\p{P}]/u', '', $text);
        $words = preg_split('/\s+/', $removedPunctuation);


        $stopWords = $this->getStopWords();

        $keywords = array_filter($words, function($word) use ($stopWords) {
            return !in_array($word, $stopWords)
                && strlen($word) > 2
                && !ctype_digit($word);
        });

        return array_values($keywords);
    }

    function getStopWords() {
        return [
            'a', 'an', 'the', 'and', 'but'
        ];
    }

    function getIdfInfo() {
         return [
            'is_cached' => Cache::has('tfidf_document_frequencies'),
            'movie_count' => Cache::get('tfidf_movie_count', 0),
            'last_calculated' => Cache::get('tfidf_last_calculated'),
            'terms_count' => count($this->documentFrequency),
        ];
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
        if (empty($favoriteGenreIds) && empty($favoriteDirectorIds)) {
            return $this->getPopularMovies($limit);
        }

        $movies = [];
        if($watchedIds) {
            foreach($watchedIds as $movieId) {
                $movies[] = $this->findSimilarMovies($movieId);

            }
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
   
        $popularMovies = Movie::where('tmdb_rating', '>', 4)->get(12);
        
        return $popularMovies;
    }

}