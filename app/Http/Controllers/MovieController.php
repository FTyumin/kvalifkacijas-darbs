<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $movies = Movie::paginate(12);
        return view('movies.index', compact('movies'));
    }

    public function show(Movie $movie)
    {
        return view('movies.show', compact('movie'));
    }

    public function display() {
        return view('movies.display');
    }

    public function search(Request $request) {
        $search = $request->input('search');

        $movies = DB::table('movies')
            ->where('name', 'like', "%{$search}%")
            ->get();

        $people = DB::table('persons')
            ->where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->get();

            // dd($people);
        return view('movies.search', compact('movies', 'search', 'people'));
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
