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
        // $recommendations = [];

        // if ($user && $user->ratings()->count() >= 5) {
        //     $recommendations['personal'] = $this->contentRecommender
        //         ->getRecommendationsForUser($user, 6);
        // } elseif ($user && $user->ratings()->count() >= 1) {
        //     $recommendations['personal'] = $this->contentRecommender
        //         ->getRecommendationsForUser($user, 6);
        // }

        // $recommendations['popular'] = Movie::select('movies.*')
        //     ->leftJoin('ratings', 'movies.id', '=', 'ratings.movie_id')
        //     ->groupBy('movies.id', 'movies.name')
        //     ->orderByRaw('AVG(ratings.rating) DESC')
        //     ->orderByRaw('COUNT(ratings.id) DESC')
        //     ->limit(6)
        //     ->with(['genres'])
        //     ->get();
        $movies = Movie::paginate(12);
        return view('movies.index', compact('movies'));
    }

    public function show(Movie $movie)
    {
        // dd($movie->director);
        return view('movies.show', compact('movie'));
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
