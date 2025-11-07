<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GenreMovieSeeder extends Seeder
{
    // public function run()
    // {
    //     // Build maps: genre name => id, movie name => id
    //     $genreMap = DB::table('genres')->pluck('id', 'name')->toArray();
    //     $movieMap = DB::table('movies')->pluck('id', 'name')->toArray();

    //     // Define genre assignments for well-known movies (adjust names if yours differ)
    //     $assignments = [
    //         'Inception' => ['Sci-Fi', 'Thriller', 'Action'],
    //         'The Godfather' => ['Crime', 'Drama'],
    //         'The Matrix' => ['Sci-Fi', 'Action'],
    //         'Parasite' => ['Drama', 'Thriller', 'Crime'],
    //         'Pulp Fiction' => ['Crime', 'Drama'],
    //         'Saving Private Ryan' => ['War', 'Drama', 'Historical'],
    //         // Add any additional movie => [genres] pairs you want seeded
    //     ];

    //     $rows = [];

    //     foreach ($assignments as $movieName => $genres) {
    //         if (!isset($movieMap[$movieName])) {
    //             // movie not present — skip
    //             continue;
    //         }
    //         $movieId = $movieMap[$movieName];

    //         foreach ($genres as $gName) {
    //             if (!isset($genreMap[$gName])) {
    //                 // If the genre isn't present (shouldn't happen if GenresTableSeeder ran), skip
    //                 continue;
    //             }
    //             $rows[] = [
    //                 'genre_id' => $genreMap[$gName],
    //                 'movie_id' => $movieId,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }
    //     }

    //     if (!empty($rows)) {
    //         // chunk insert to be safe with larger lists
    //         foreach (array_chunk($rows, 500) as $chunk) {
    //             DB::table('genre_movie')->insert($chunk);
    //         }
    //     }
    // }

    public function run() {
        $faker = Faker::create();

        $genreIds = DB::table('genres')->pluck('id')->toArray();
        $movieIds = DB::table('movies')->pluck('id')->toArray();

        foreach ($movieIds as $movieId) {
            // attach between 2 and 5 genres per movie
            $numGenres = $faker->numberBetween(2, min(5, count($genreIds)));
            // pick unique actors for this movie
            $selected = $faker->randomElements($genreIds, $numGenres);
            // $selected = collect($genreIds)->random($numGenres)->toArray();
            foreach ($selected as $genreId) {
                $pivotRows[] = [
                    'genre_id' => $genreId,
                    'movie_id' => $movieId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

        }
        $chunks = array_chunk($pivotRows, 500);
        foreach ($chunks as $chunk) {
            DB::table('genre_movie')->insert($chunk);
        }
    }
}
