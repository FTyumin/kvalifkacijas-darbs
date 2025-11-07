<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\TmdbApiClient;
use App\Models\Genre;
use App\Models\Movie;
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
    protected $description = 'Command description';
    protected $apiClient;
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $seeder = new GenreSeeder();
        $seeder->run();

        $api = new TmdbApiClient;
        $genres = Genre::all()->keyBy('name');

        $n = 100;
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

        DB::table('movies')->upsert($movies->all(), ['id'], ['name', 'year', 'description', 'poster_url']);

        $movieGenres = [];
        $movieGenreData = [];
        // \Log::info($movies);

        foreach ($movies as $movie) {
            $movie_info = $api->getMovieWithExtras($movie['id']);

            // Dati: [ ['id'=>28,'name'=>'Action'], ['id'=>12,'name'=>'Adventure'] ]
            $movieGenres = $movie_info['genres'] ?? [];

            $genreIds = [];

            $movieModel = Movie::find($movie['id']);
            foreach ($movieGenres as $genreData) {
                // Atrast zanru pec nosaukuma no datubazes
                $genre = $genres->firstWhere('name', $genreData['name']);

                // Ja zanrs nav atrasts, izveidot 
                if (!$genre) {
                    $genre = Genre::create(['name' => $genreData['name']]);
                    $genres->push($genre);
                }

                $movieGenreData[] = [
                    'movie_id' => $movieModel->id,
                    'genre_id' => $genre->id
                ];
            }
        }
        DB::table('genre_movie')->upsert($movieGenreData, ['movie_id', 'genre_id']);
    }
}
