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
    protected $signature = 'app:insert-data';

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
        $api = new TmdbApiClient;
        $genres = Genre::all()->keyBy('name');

        $n = 30;
        $data = $api->getTopMovies($n, ['method' => 'discover']);

        foreach($data as $movie) {
            Movie::updateOrCreate(
                ['id' => $movie['id']],
                [
                    'id' => $movie['id'],
                    'name' => $movie['title'],
                    'year' => !empty($movie['release_date']) ? substr($movie['release_date'],0,4) : null,
                    'description' => $movie['overview'],
                    'language' => $movie['original_language'],
                    'tmdb_rating' => $movie['vote_average'],
                    'poster_url' => $movie['poster_path'],
                ]
                );
        }

        $movieGenres = [];
        // $existingGenres = Genre::whereIn('name', $genreNames)->get()->keyBy('name');
        foreach ($data as $movie) {
            $movie_info = $api->getMovieWithExtras($movie['id']);

            $actor_info = array_slice($movie_info['credits']['cast'], 0, 5);

            $crew = $movie_info['credits']['crew'];
            $director = array_filter($crew, function($person) {
                return $person['job'] === 'Director';
            });
            
            $director = reset($director);

            $nameParts = explode(" ", $director['name']);
            $director = Person::updateOrCreate(
                [
                    'id' => $director['id'],
                ],
                [
                    'first_name' => array_shift($nameParts),
                    'last_name' => implode(' ', $nameParts),
                    'type' => 'director'
                ]
            );
            $movie = Movie::find($movie['id']);
            $movie->director_id = $director->id;
            $movie->duration = $movie_info['runtime'];
            $movie->trailer_url = $api->trailerKey($movie->id);
            $movie->save();

            // Data: [ ['id'=>28,'name'=>'Action'], ['id'=>12,'name'=>'Adventure'] ]
            $movieGenres = $movie_info['genres'] ?? [];
            foreach ($actor_info as $actor) {
                $nameParts = explode(" ", $actor['name']);
                $actor_data = $api->personData($actor['id']);
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

            // Sync all actors at once (won't create duplicates)
            $movie = Movie::find($movie['id']);
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
        }
    }
}
