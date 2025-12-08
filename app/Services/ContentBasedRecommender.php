<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ContentBasedRecommender
{
    private $documentFrequency = []; // IDF values
    private $allMoviesKeywords = [];
    private $totalDocuments;

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
        $actors1 = DB::table('actor_movie')
            ->where('movie_id', $movie1->id)
            ->get('actor_id');

        $actors2 = DB::table('actor_movie')
            ->where('movie_id', $movie2->id)
            ->get('actor_id');
        
        // Get director IDs as sets
        $director1 = [$movie1->director->id];
        $director2 = [$movie2->director->id];
        
        $genres1 = $genres1->pluck('genre_id')->toArray();
        $genres2 = $genres2->pluck('genre_id')->toArray();

        $actors1 = $actors1->pluck('actor_id')->toArray();
        $actors2= $actors2->pluck('actor_id')->toArray();

        // Calculate Jaccard index for each component
        $genreJaccard = $this->jaccardIndex($genres1, $genres2);
        $actorJaccard = $this->jaccardIndex($actors1, $actors2);
        $directorJaccard = $this->jaccardIndex($director1, $director2);
        $descriptionSimilarity = $this->calculateDescriptionSimilarity($movie1, $movie2);
        // Weighted combination
        $similarity = (0.25 * $genreJaccard) + 
                      (0.2 * $directorJaccard) + 
                      (0.25 * $actorJaccard); 
                    //   (0.3 * $descriptionSimilarity);
        
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
            'a', 'an', 'the', 'and', 'but', 'or', 'as', 'at', 'be', 'by',
            'for', 'from', 'has', 'he', 'in', 'is', 'it', 'its', 'of', 'on',
            'that', 'to', 'was', 'will', 'with', 'they', 'their', 'this',
            'have', 'had', 'what', 'when', 'where', 'who', 'which', 'why',
            'how', 'all', 'are', 'been', 'can', 'could', 'do', 'does', 'did',
            'his', 'her', 'his', 'him', 'she', 'them', 'there', 'these',
            'those', 'would', 'should', 'into', 'through', 'about', 'after',
            'before', 'between', 'under', 'over', 'up', 'down', 'out', 'off'
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
        if(!$user) {
            return;
        }
        
        // retrieve user's favorite genres
        $favoriteGenres = $user->favoriteGenres;
        // $favoriteActorIds = $user->favoriteActors->pluck('id')->toArray();
        // $favoriteDirectorIds = $user->favoriteDirectors->pluck('id')->toArray();
        
        //movies that user shouldn't get as recommendations
        $watchedIds = $user->seenMovies()->pluck('markable_id')->toArray();
        $watchlistIds = $user->wantToWatch()->pluck('markable_id')->toArray();
        $favoriteIds = $user->favorites()->pluck('markable_id')->toArray();
        $excludeIds = array_merge($watchedIds, $watchlistIds);

        // get user's high ratings(4-5 stars)
        $reviews = $user->reviews()->where('rating', '>=', 4)->get();
        $allRecommendations = [];

        $favoriteSimilar = [];
        if($favoriteIds) {
            foreach ($favoriteIds as $id) {
                $favoriteSimilar = array_merge($favoriteSimilar, $this->findSimilarMovies($id, 5));
            }

            foreach ($favoriteSimilar as &$movieData) {
                $movieData['similarity'] *= 1.4;
            }
            
            // $allRecommendations = array_merge();
            dd(count($allRecommendations));
        } elseif ($reviews) {
            // similarity boost: 0.3
            $reviewSimilar = [];
            $movieIds = $reviews->pluck('movie_id')->toArray();
            foreach($movieIds as $id) {
                $reviewSimilar = array_merge($reviewSimilar, $this->findSimilarMovies($id, 5));
            }

            foreach ($reviewSimilar as &$movieData) {
                $movieData['similarity'] *= 1.3;
            }

            // $allRecommendations
            
        } elseif($watchedIds) {
            // similarity boost - 0.05
            $seenList = [];
            foreach($watchedIds as $id) {
                $seenList = array_merge($seenList, $this->findSimilarMovies($id, 5));
            }

            foreach ($seenList as &$movieData) {
                $movieData['similarity'] *= 1.05;
            }

        } elseif($favoriteGenres) {
            // similarity boost - 0.2
            $genreList = [];
            foreach($favoriteGenres as $genre) {
                $genreList = array_merge($genreList, $this->getGenreMovies($genre, 5));
            }

            foreach ($genreList as &$movieData) {
                $movieData['similarity'] *= 1.2;
            }


        } else {
            $this->getRecommendationsForNewUser($favoriteGenres, 10);
        }

        $allRecommendations = array_merge($favoriteSimilar, $reviewSimilar, $seenList, $genreList);

        // $allRecommendations = $favoriteSimilar;

             
        // echo "debug";

        // if($watchedIds) {

        //     foreach ($watchedIds as $movieId) {
        //         $similarMovies = $this->findSimilarMovies($movieId, $limit);
                
        //         foreach ($similarMovies as $movie) {
        //             // skip if user has already seen this movie
        //             if (in_array($movie["movie"]['id'], $excludeIds)) {
        //                 continue;  
        //             }
                    
        //             // add to recommendations
        //             $movieId = $movie["movie"]["id"];
        //             $movieObj = Movie::with('genres')->find($movieId);

        //             $baseScore = $movie["similarity"];
        //             $movieGenreIds = $movieObj->genres->pluck('id')->toArray();

        //             $hasGenre = !empty(array_intersect($movieGenreIds, $favoriteGenreIds));

        //             // if movie has user's favorite genres, increase similarity
        //             $finalScore = $hasGenre ? : $baseScore * 1.2;
        //             $movie["similarity"] = $finalScore;
                    
        //             // if movie already in list, keep the one with higher similarity
        //             if (!isset($allRecommendations[$movieId]) || 
        //             $movie["similarity"] > $allRecommendations[$movieId]["similarity"]) {
        //                 $allRecommendations[$movieId] = $movie;
        //             }
        //         }
        //     }
        // } else {
           
        // }
        

        usort($allRecommendations, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        
        // return top recommendations from array
        return array_slice($allRecommendations, 0, $limit);
    }

    private function getRecommendationsForNewUser($favoriteGenreIds, $limit) {
        if (empty($favoriteGenreIds)) {
            // no preferences - show popular movies
            return $this->getPopularMovies($limit);
        }
        echo "debug";
        
        return Movie::whereHas('genres', function($query) use ($favoriteGenreIds) {
                $query->whereIn('genres.id', $favoriteGenreIds);
            })
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->having('reviews_count', '>=', 5)
            ->orderByDesc('reviews_avg_rating')
            ->limit($limit)
            ->get()
            ->map(function($movie) {
                return [
                    'id' => $movie->id,
                    'name' => $movie->name,
                    'value' => $movie->reviews_avg_rating / 5, // Normalize to 0-1
                    'reason' => 'Matches your favorite genres',
                ];
            });
    }

    private function getGenreMovies(Genre $genre) {
        return $genre->movies()->limit(12)->get();
    }

    function getPopularMovies($limit) {
   
        $popularMovies = Movie::where('tmdb_rating', '>', 4)->limit(10)->get();
        return $popularMovies;
    }

}