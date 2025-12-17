<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\MovieList;
use App\Models\Suggestion;
use App\Models\Person;
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
        $movies = Movie::all()->take(5);
        $genres = Genre::inRandomOrder()->take(4)->withCount('movies')->get();

        $lists = MovieList::visibleTo(auth()->user())->with('user')->get();

        $id = auth()->id();
        $userRecommendations = [];

        if($id) {
            $userRecommendations = $this->contentRecommender->getRecommendationsForUser($id, 8);
            Cache::remember("user:{$id}:recs", 3600, function () use ($id) {
                return $this->contentRecommender->getRecommendationsForUser($id, 8);
            });

        } 
        return view('home', compact('movies', 'genres', 'lists', 'userRecommendations'));
    }

    public function index(Request $request) {
        $directors = Person::where('type', 'director')->get();
        $genres = Genre::all();
        $years = ['1970', '1971'];
       $query = Movie::query()->with(['genres', 'actors']);

    if ($request->filled('genres')) {
        $query->whereHas('genres', function ($q) use ($request) {
            $q->whereIn('genres.id', $request->genres);
        });
    }

    if ($request->filled('directors')) {
        $query->whereHas('director', function ($q) use ($request) {
            $q->whereIn('persons.id', $request->directors);
        });
    }

    if ($request->filled('min_rating')) {
        $query->where('rating', '>=', $request->min_rating);
    }

    if ($request->filled('year')) {
        $query->where('year', '>=' , $request->year);
    }

    switch ($request->sort) {
        case 'year':
            $query->orderBy('year', 'desc');
            break;
        case 'name':
            $query->orderBy('title');
            break;
        default:
            $query->orderBy('rating', 'desc');
    }

    $movies = $query->paginate(20)->withQueryString();

        return view('movies.index', compact('movies', 'genres', 'years', 'directors'));
    }

    public function show(Movie $movie)
    {
        $similarMovies = $this->contentRecommender->findSimilarMovies($movie->id);
        $id = $movie->id;
        Cache::remember("movie:{$id}:recs", 3600, function () use ($id) {
                return $this->contentRecommender->findSimilarMovies($id, 8);
            });
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

    public function add() {
        $genres = Genre::all();
        return view('movies.add', compact('genres'));
    }

    public function store(Request $request) {
        $movie = Movie::find($request->movie_id);
        if($movie) {
            return redirect()->back()->with('error', 'Movie already exists');
        }
        $genres = Genre::all()->keyBy('name');
        $movie_info = $this->apiClient->getMovieWithExtras($request->movie_id);
        
        Movie::updateOrCreate(
                ['id' => $movie_info['id']],
                [
                    'id' => $movie_info['id'],
                    'name' => $movie_info['title'],
                    'year' => !empty($movie_info['release_date']) ? substr($movie_info['release_date'],0,4) : null,
                    'description' => $movie_info['overview'],
                    'language' => $movie_info['original_language'],
                    'tmdb_rating' => $movie_info['vote_average'],
                    'poster_url' => $movie_info['poster_path'],
                ]
                );
            $actor_info = array_slice($movie_info['credits']['cast'], 0, 5);

            $crew = $movie_info['credits']['crew'];
            $director = array_filter($crew, function($person) {
                return $person['job'] === 'Director';
            });
            
            $director = reset($director);

            $nameParts = explode(" ", $director['name']);
            $director_data = $this->apiClient->personData($director['id']);
            $director = Person::updateOrCreate(
                [
                    'id' => $director['id'],
                ],
                [
                    'first_name' => array_shift($nameParts),
                    'last_name' => implode(' ', $nameParts),
                    'type' => 'director',
                    'profile_path' => $director_data['profile_path'],
                    'biography' => $director_data['biography'],
                ]
            );
            $movie = Movie::find($movie_info['id']);
            $movie->director_id = $director->id;
            $movie->duration = $movie_info['runtime'];
            $movie->trailer_url = $this->apiClient->trailerKey($movie->id);
            $movie->save();

            $movieGenres = $movie_info['genres'] ?? [];
            foreach ($actor_info as $actor) {
                $nameParts = explode(" ", $actor['name']);
                $actor_data = $this->apiClient->personData($actor['id']);
                Person::updateOrCreate(
                    ['id' => $actor['id']],
                    [
                        'first_name' => array_shift($nameParts),
                        'last_name' => implode(' ', $nameParts),
                        'type' => 'actor',
                        'profile_path' => $actor_data['profile_path'],
                        'biography' => $actor_data['biography'],
                    ]
                );

            }

            $actorIds = collect($actor_info)->pluck('id');
            $movie->actors()->syncWithoutDetaching($actorIds);
            
            $genreIds = collect($movieGenres)->map(function($genreData) use (&$genres) {  // Note the & reference
                $genre = $genres->firstWhere('name', $genreData['name']);
                
                // create genre if not found
                if (!$genre) {
                    $genre = Genre::create(['name' => $genreData['name']]);
                    $genres->push($genre);  
                }
                
                return $genre->id;
            })->toArray();

            $movie->genres()->syncWithoutDetaching($genreIds);

        return redirect()->route('movies.show', $movie);
    }

    public function storeSuggestion(Request $request) {
        $id = auth()->id();

        $request->validate([
            'title' => 'required|string|min:3|max:30',
        ]);
        
        Suggestion::create([
            'user_id' => $id,
            'title' => $request->title,
        ]);

        session()->flash('success', 'Your suggestion has been sent!');
        return redirect('');
    }

    public function sendSuggestion(Request $request) {
        return view('suggestion');
    }
}
