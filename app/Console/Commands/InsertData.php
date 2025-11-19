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
        $genreSeeder = new GenreSeeder();
        $genreSeeder->run();

        $api = new TmdbApiClient;
        $genres = Genre::all()->keyBy('name');

        $n = 3;
        $data = $api->getTopMovies($n, ['method' => 'top-rated']);
        $movies = collect($data ?? [])->map(function ($r) {
            return [
                'id' => $r['id'],
                'name' => $r['title'] ?? null,
                'year' => !empty($r['release_date']) ? substr($r['release_date'],0,4) : null,
                'description' => $r['overview'],
                'poster_url' => $r['poster_path'],
            ];
        });

        foreach ($movies as $movieData) {
            \Log::info($movieData['id']);
            Movie::updateOrCreate(
                ['id' => $movieData['id']],
                [
                    'id' => $movieData['id'],
                    'name' => $movieData['name'],
                    'year' => $movieData['year'],
                    'description' => $movieData['description'],
                    'poster_url' => $movieData['poster_url'],
                    
                ]
            );
        }

        $movieGenres = [];
        // \Log::info($movies);
        
        foreach ($movies as $movie) {
            $movieGenreData = [];
            $movie_info = $api->getMovieWithExtras($movie['id']);

            $actor_info = array_slice($movie_info['credits']['cast'], 0, 5);

            $crew = $movie_info['credits']['crew'];
            $director = array_filter($crew, function($person) {
                return $person['job'] === 'Director';
            });

            $director = reset($director);
            
            \Log::info($director);
            $nameParts = explode(" ", $director['name']);
            $director = Person::updateOrCreate(
                [
                    'first_name' => array_shift($nameParts),
                    'last_name' => implode(' ', $nameParts),
                    'type' => 'director'
                ],
                []
            );
            $movie = Movie::find($movie['id']);
            $movie->director_id = $director->id;


            // Dati: [ ['id'=>28,'name'=>'Action'], ['id'=>12,'name'=>'Adventure'] ]
            $movieGenres = $movie_info['genres'] ?? [];

            $genreIds = [];
            
            foreach($actor_info as $actor) {
                $nameParts = explode(" ", $actor['name']);
                Person::updateOrCreate(
                    ['id' => $actor['id']],
                    [
                        'id' => $actor['id'],
                        'first_name' => array_shift($nameParts),
                        'last_name' => implode(' ', $nameParts),
                        'type' => 'actor',
                    ]
                );

                DB::table('actor_movie')->insert(['actor_id' => $actor['id'], 'movie_id' => $movie['id']]);
            }

            // $movieModel = Movie::find($movie->id);
            foreach ($movieGenres as $genreData) {
                // Atrast zanru pec nosaukuma no datubazes
                $genre = $genres->firstWhere('name', $genreData['name']);

                // Ja zanrs nav atrasts, izveidot 
                if (!$genre) {
                    $genre = Genre::create(['name' => $genreData['name']]);
                    $genres->push($genre);
                }

                $movieGenreData[] = [
                    'movie_id' => $movie['id'],
                    'genre_id' => $genre->id
                ];
            }

            DB::table('genre_movie')->upsert($movieGenreData, ['movie_id', 'genre_id']);
        }
    }
}
