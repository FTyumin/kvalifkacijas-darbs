<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\MovieList;
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

    public function home() {
        $movies = Movie::all()->take(4);
        $genres = Genre::inRandomOrder()->take(4)->get();

        $lists = MovieList::all()->take(4);

        $id = auth()->id();
        $userRecommendations = [];

        if($id) {
            // $userRecommendations = $this->contentRecommender->getRecommendationsForUser($id, 8);
            $userRecommendations = Cache::remember("user:{$id}:recs", 3600, function () use ($id) {
                return $this->contentRecommender->getRecommendationsForUser($id, 8);
            });

        } 
        return view('home', compact('movies', 'genres', 'lists', 'userRecommendations'));
    }

    public function index(Request $request) {
        $movies = Movie::paginate(12);
        $query = Movie::with('genres');

        if($request->filled('genre')) {
            $query->whereHas('genres', function($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }

        if ($request->filled('min_rating')) {
            $query->where('tmdb_rating', '>=', $request->min_rating);
        }
    
        // Year filter
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $movies = $query->paginate(12)->withQueryString(); // withQueryString preserves filters in pagination
        $genres = Genre::orderBy('name')->get();
        $years = Movie::distinct()->orderBy('year', 'desc')->pluck('year');

        return view('movies.index', compact('movies', 'genres', 'years'));
    }

    public function show(Movie $movie)
    {
        $similarMovies = $this->contentRecommender->findSimilarMovies($movie->id);
        // dd($similarMovies);
        return view('movies.show', compact('movie', 'similarMovies'));
    }

    public function search(Request $request) {
        $search = $request->input('search');

        $movies = Movie::where('name', 'like', "%{$search}%")
            ->with('genres')
            ->get();

        $people = DB::table('persons')
            ->where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->get();

        return view('movies.search', compact('movies', 'search', 'people'));
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

    public function add() {
        $genres = Genre::all();
        

        return view('movies.add', compact('genres'));
    }

    public function store(Request $request) {
        // dd($request->genres);
        Movie::create([
            'name' => $request->title,
            'description' => $request->description,
            'year' => $request->year,
            'poster_url' => $request->poster_url,
        ]);

    }
}
