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
    function getCollaborativeRecommendations($userId, $limit = 10) {
        $matrix = buildUserItemMatrix();
        
        if (!isset($matrix[$userId])) {
            return []; // Lietotajam nav vertejumu
        }
        
        $userRatings = $matrix[$userId];
        
        // Atrast lidzigus lietotajus pec filmu vertejumiem
        $similarUsers = [];
        
        foreach ($matrix as $otherUserId => $otherRatings) {
            if ($otherUserId == $userId) continue;
            
            $similarity = calculatePearsonCorrelation($userRatings, $otherRatings);
            
            if ($similarity > 0.3) { // Vismaz 0.3
                $similarUsers[] = [
                    'user_id' => $otherUserId,
                    'similarity' => $similarity
                ];
            }
        }
        
        // Sakartot pec rezultatiem
        usort($similarUsers, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        
        // Panemt 50 lidzigakus lietotajus
        $similarUsers = array_slice($similarUsers, 0, 50);
        
        // Atrast filmas, ko lietotajis nav novertejis
        $allMovieIds = Movie::pluck('id')->toArray();
        $ratedMovieIds = array_keys($userRatings);
        $unratedMovieIds = array_diff($allMovieIds, $ratedMovieIds);
        
        // Izveidot potencialo reitingu
        $predictions = [];
        
        foreach ($unratedMovieIds as $movieId) {
            $predictedRating = predictRating($userId, $movieId, $similarUsers, $matrix);
            
            if ($predictedRating !== null && $predictedRating >= 3.5) {
                $predictions[] = [
                    'movie_id' => $movieId,
                    'predicted_rating' => $predictedRating
                ];
            }
        }
        
        // Sakartot pec reitingiem
        usort($predictions, function($a, $b) {
            return $b['predicted_rating'] <=> $a['predicted_rating'];
        });
        
        // Atgriezt pirmas filmas
        return array_slice($predictions, 0, $limit);
    }
    function buildUserItemMatrix() {

        $ratings = Review::all();

        $matrix = [];

        foreach ($reviews as $review) {
            $userId = $review->user_id;
            $movieId = $review->movie_id;
            $ratingValue = $review->rating;

            if(!isset($matrix[$userId])) {
                $matrix[$userId] = [];
            }

            $matrix[$userId][$movieId] = $ratingValue;
        }
        return $matrix;
    }

    function calculatePearsonCorrelation($userARatings, $userBRatings) {
        $commonMovies = array_intersect_key($userARatings, $userBRatings);
        
        if(count($commonMovies) < 3) {
            return 0;
        }

        $avgA = array_sum(array_intersect_key($userARatings, $commonMovies)) / count($commonMovies);
        $avgB = array_sum(array_intersect_key($userBRatings, $commonMovies)) / count($commonMovies);

        foreach($commonMovies as $movieId => $ratingA) {
            $ratingB = $userBRatings[$movieId];

            $diffA = $ratingA - $avgA;
            $diffB = $ratingB - $avgB;

            $numerator += $diffA * $diffB;
            $sumSquaredA += $diffA * $diffA;
            $sumSquaredB += $diffB * $diffB;
        }
        $denominator = sqrt($sumSquaredA * $sumSquaredB);

        if($denominator == 0) {
            return 0;
        }

        return $numerator / $denominator;
    }

    function predictRating($targetUserId, $movieId, $similarUsers, $matrix) {
        $weightedSum = 0;
        $similaritySum = 0;

        foreach($similarUsers as $similarUser) {
            $userId = $similarUser['user_id'];
            $similarity = $similarUser['similarity'];

            if(isset($matrix[$userId][$movieId])) {
                $rating = $matrix[$userId][$movieId];

                $weightedSum += $rating * $similarity;
                $similaritySum += abs($similarity); 
            }
        }

        if($similaritySum == 0) {
            return null;
        }

        return $weightedSum / $similaritySum;
    }

}