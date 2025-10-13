<?php
// App/Http/Controllers/Web/MovieController.php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Services\ContentBasedRecommender;
use App\Services\CollaborativeFilteringRecommender;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $contentRecommender;
    protected $collaborativeRecommender;

    public function __construct(
        ContentBasedRecommender $contentRecommender,
        CollaborativeFilteringRecommender $collaborativeRecommender
    ) {
        $this->contentRecommender = $contentRecommender;
        $this->collaborativeRecommender = $collaborativeRecommender;
    }

    /**
     * Homepage with recommendations
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $recommendations = [];

        if ($user && $user->ratings()->count() >= 5) {
            $recommendations['personal'] = $this->collaborativeRecommender
                ->getRecommendationsForUser($user, 6);
        } elseif ($user && $user->ratings()->count() >= 1) {
            $recommendations['personal'] = $this->contentRecommender
                ->getRecommendationsForUser($user, 6);
        }

        $recommendations['trending'] = $this->collaborativeRecommender
            ->getTrendingMovies(6);

        $recommendations['popular'] = Movie::select('movies.*')
            ->leftJoin('ratings', 'movies.id', '=', 'ratings.movie_id')
            ->groupBy('movies.id')
            ->orderByRaw('AVG(ratings.rating) DESC')
            ->orderByRaw('COUNT(ratings.id) DESC')
            ->limit(6)
            ->with(['genres'])
            ->get();

        return view('movies.index', compact('recommendations'));
    }

    /**
     * Show movie details with recommendations
     */
    public function show(Movie $movie)
    {
        $movie->load(['genres', 'directors', 'actors', 'ratings']);

        // Get similar movies
        $similarMovies = $this->contentRecommender
            ->getRecommendationsForMovie($movie->id, 6);

        $fanFavorites = $this->collaborativeRecommender
            ->getMoviesLikedByFans($movie->id, 6);

        return view('movies.show', compact('movie', 'similarMovies', 'fanFavorites'));
    }

    /**
     * Rate a movie (for web interface)
     */
    public function rate(Request $request, Movie $movie)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5'
        ]);

        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $user->ratings()->updateOrCreate(
            ['movie_id' => $movie->id],
            ['rating' => $request->rating]
        );

        return redirect()
            ->route('movies.show', $movie)
            ->with('success', 'Rating saved successfully!');
    }

    /**
     * User's personal recommendations page
     */
    public function recommendations(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $userRatingsCount = $user->ratings()->count();
        
        if ($userRatingsCount >= 5) {
            $recommendations = $this->collaborativeRecommender
                ->getRecommendationsForUser($user, 20);
            $method = 'Collaborative Filtering';
        } else {
            $recommendations = $this->contentRecommender
                ->getRecommendationsForUser($user, 20);
            $method = 'Content-Based';
        }

        return view('movies.recommendations', compact('recommendations', 'method', 'userRatingsCount'));
    }
}