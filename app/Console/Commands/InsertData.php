<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\TmdbApiClient;
use App\Models\Genre;
use App\Models\Movie;

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
        $api = new TmdbApiClient;
        $genres = Genre::all();

        $n = 10;
        $data = $api->getTopMovies($n, ['method' => 'top-rated']);
        $movies = collect($data ?? [])->map(function ($r) use ($api) {
            return [
                'id' => $r['id'],
                'name' => $r['title'] ?? null,
                'year' => !empty($r['release_date']) ? substr($r['release_date'],0,4) : null,
                'description' => $r['overview'],
                'poster_url' => $r['poster_path'],
            ];
        })->all();
        DB::table('movies')->insert($movies);

        $movieGenres = [];
        $movieGenreData = [];
        foreach ($movies as $movie) {
            $movie_info = $api->getMovieWithExtras($movie['id']);

            // Dati: [ ['id'=>28,'name'=>'Action'], ['id'=>12,'name'=>'Adventure'] ]
            $movieGenres = $movie_info['genres'] ?? [];

            $genreIds = [];

            foreach ($movieGenres as $genreData) {
                // Atrast zanru pec nosaukuma no datubazes
                $genre = $genres->firstWhere('name', $genreData['name']);

                // Ja zanrs nav atrasts, izveidot 
                if (!$genre) {
                    $genre = Genre::create(['name' => $genreData['name']]);
                    $genres->push($genre);
                }

                $genreIds[] = $genre->id;
            }

            // Pievienot zanrus filmam(many-to-many)
            $movieModel = Movie::find($movie['id']);
            \Log::info($genreIds);
            foreach ($genreIds as $genreId) { 
                $movieGenreData[] = [
                    'movie_id' => $movieModel->id,
                    'genre_id' => $genreId
                ];
            }
        }
        \Log::info($movieGenreData);

        DB::table('genre_movie')->insert($movieGenreData);

        // foreach($movies as $movie) {
        //     $movie_info = $api->getMovieWithExtras($movie['id']);

        //     $actors = $movie_info['credits']['cast'];
        //     foreach ($actors as $actor) {
        //         echo $actor['name'] . ' as ' . $actor['character'];
        //     }
        //     // DB::table
        // }


    }
}
