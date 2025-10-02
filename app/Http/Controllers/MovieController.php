<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Services\ContentBasedRecommender;
use App\Services\CollaborativeFilteringRecommender;

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
            ->groupBy('movies.id', 'movies.name')
            ->orderByRaw('AVG(ratings.rating) DESC')
            ->orderByRaw('COUNT(ratings.id) DESC')
            ->limit(6)
            ->with(['genres'])
            ->get();

        return view('movies.index', compact('recommendations'));
    }

    public function show($movieID) {
        $movie = Movie::findOrFail($movieID);
        $movie->load('genres');

        $recommendations = $this->contentRecommender->findSimilarMovies($movie->id, 3);

        $ids = array_map(fn($r) => $r['id'] ?? ($r['movie_id'] ?? null), $recommendations);

        if (!empty($ids)) {
            $movies = Movie::whereIn('id', $ids)
                ->with(['genres', 'director', 'actors']) 
                ->get()
                ->keyBy('id'); 

            foreach ($recommendations as $i => $item) {
                $id = $item['id'] ?? ($item['movie_id'] ?? null);
                if ($id && isset($movies[$id])) {
                    $m = $movies[$id];

                    // Atrastam filmam pielikt vajadzigos laukus
                    $recommendations[$i]['title']       = $m->name;
                    $recommendations[$i]['description'] = $m->description;
                    $recommendations[$i]['year']        = $m->year;
                    $recommendations[$i]['rating']      = $m->rating;
                    $recommendations[$i]['img_url']     = $m->poster_url; 

                    $recommendations[$i]['genres'] = $m->genres->pluck('name')->all();
                    $recommendations[$i]['director'] = $m->director ? $m->director->name : null;
                } 
            }
        }
        return view('movies.show', compact('movie', 'recommendations'));
    }

    public function display() {
        return view('movies.display');
    }

    public function search(Request $request) {
        $search = $request->input('search');

        $results = Movie::with(['director', 'actors'])
            ->where('name', 'like', "%{$search}%")
            ->orWhereHas('director', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('actors', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->get();

        return view('movies.index', compact('results', 'search'));
    }

    public function recommendations(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $userRatingsCount = $user->reviews()->count();
        
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
