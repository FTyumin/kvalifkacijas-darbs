<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Services\ContentBasedRecommender;
use App\Services\TmdbApiClient;

class MovieController extends Controller
{
    protected $contentRecommender;
    protected $apiClient;

    public function __construct(
        ContentBasedRecommender $contentRecommender,
        TmdbApiClient $apiClient
    ) {
        $this->contentRecommender = $contentRecommender;
        $this->apiClient = $apiClient;
    }

    public function index(Request $request) {
        $user = $request->user();
        $recommendations = [];

        if ($user && $user->ratings()->count() >= 5) {
            $recommendations['personal'] = $this->contentRecommender
                ->getRecommendationsForUser($user, 6);
        } elseif ($user && $user->ratings()->count() >= 1) {
            $recommendations['personal'] = $this->contentRecommender
                ->getRecommendationsForUser($user, 6);
        }


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

        // $userRatingsCount = $user->reviews()->count();
        
        $recommendations = $this->contentRecommender
            ->getRecommendationsForUser($user, 20);
        $method = 'Content-Based';

        return view('movies.recommendations', compact('recommendations', 'method', 'userRatingsCount'));
    }

    public function topPage(TmdbApiClient $tmdb)
    {
        $n = 100;
        $data = $tmdb->getTopMovies($n, ['method' => 'top-rated']);
        $movies = collect($data ?? [])->map(function ($r) use ($tmdb) {
            return [
                'id' => $r['id'] ?? null,
                'title' => $r['title'] ?? null,
                'poster' => isset($r['poster_path']) ? $tmdb->posterUrl($r['poster_path'],'w500') : asset('images/cinema.webp'),
                'year' => !empty($r['release_date']) ? substr($r['release_date'],0,4) : null,
            ];
        })->all();

        return view('movies.top', compact('movies'));
    }
}
