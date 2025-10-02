<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\Director;
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

        $allDirectors = Director::all();
        foreach ($allDirectors as $director) {
            $key = 'director_' . $director->id;
            // dd($movie->director->id);
            $features[$key] = (int) ($movie->director?->id === $director->id);
        }

        $topActors = Actor::whereIn('id', function($query) {
            $query->select('actor_id')
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



}