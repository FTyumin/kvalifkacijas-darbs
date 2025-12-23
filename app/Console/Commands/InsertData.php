<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\TmdbApiClient;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Person;
use Database\Seeders\GenreSeeder;

class InsertData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:insert-data {count=200}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load movie, actor data from TMDB API to local DB';
    protected $apiClient;
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        $api = new TmdbApiClient;
        $genres = Genre::all()->keyBy('name');

        $n = (int) $count;
        $data = $api->getTopMovies($n, ['method' => 'discover']);

        foreach($data as $tmdbMovie) {
            Movie::updateOrCreate(
                ['tmdb_id' => $tmdbMovie['id']],
                [
                    'name' => $tmdbMovie['title'],
                    'year' => !empty($tmdbMovie['release_date']) ? substr($tmdbMovie['release_date'],0,4) : null,
                    'description' => $tmdbMovie['overview'],
                    'language' => $tmdbMovie['original_language'],
                    'tmdb_rating' => $tmdbMovie['vote_average'],
                    'poster_url' => $tmdbMovie['poster_path'],
                ]
                );
        }

        foreach ($data as $tmdbMovie) {
            $movie_info = $api->getMovieWithExtras($tmdbMovie['id']);

            $actor_info = array_slice($movie_info['credits']['cast'], 0, 5);

            $crew = $movie_info['credits']['crew'];
            $directors = array_filter($crew, fn($p) => $p['job'] === 'Director');

            $movie = Movie::where('tmdb_id', $tmdbMovie['id'])->first();

            $directorIdsWithRole = [];

            foreach($directors as $director) {
                $director_data = $api->personData($director['id']);
                $nameParts = explode(" ", $director['name']);
                $director = Person::UpdateOrCreate(
                    ['tmdb_id' => $director['id']],
                    [
                        'first_name' => array_shift($nameParts),
                        'last_name' => implode(' ', $nameParts),
                        'profile_path' => $director_data['profile_path'],
                        'biography' => $director_data['biography'],
                    ]
                );
                // add role
                $directorIdsWithRole[$director->id] = ['role' => 'director'];
            }

            if (!empty($directorIdsWithRole)) {
                $movie->people()->syncWithoutDetaching($directorIdsWithRole);
            }
            
            $movie->duration = $movie_info['runtime'];
            $movie->trailer_url = $api->trailerKey($movie->tmdb_id);
            $movie->save();

            // Data: [ ['id'=>28,'name'=>'Action'], ['id'=>12,'name'=>'Adventure'] ]
            $actorIdsWithRole = [];

            foreach ($actor_info as $actor) {
                $nameParts = explode(" ", $actor['name']);
                $actor_data = $api->personData($actor['id']);
                $person = Person::updateOrCreate(
                    ['tmdb_id' => $actor['id']],
                    [
                        'first_name' => array_shift($nameParts),
                        'last_name' => implode(' ', $nameParts),
                        'profile_path' => $actor_data['profile_path'],
                        'biography' => $actor_data['biography'],
                    ]
                );
                // add role
                $actorIdsWithRole[$person->id] = ['role' => 'actor'];
            }

            // Sync all actors at once 
            if (!empty($actorIdsWithRole)) {
                $movie->people()->syncWithoutDetaching($actorIdsWithRole);
            }

            $movieGenres = $movie_info['genres'] ?? [];
            $genreIds = collect($movieGenres)->map(function($genreData) use (&$genres) {  
                $genre = $genres->firstWhere('name', $genreData['name']);
                
                // create genre, if not found
                if (!$genre) {
                    $genre = Genre::create(['name' => $genreData['name']]);
                    $genres->push($genre);  
                }
                
                return $genre->id;
            })->toArray();

            $movie->genres()->syncWithoutDetaching($genreIds);
        }
    }
}
