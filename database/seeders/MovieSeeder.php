<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Grab all director IDs to assign some movies
        $directorIds = DB::table('directors')->pluck('id')->toArray();

        $movies = [];
        $count = 25; // number of movies

        for ($i = 0; $i < $count; $i++) {
            // Year between 1950 and current year
            $year = (int) $faker->year($max = 'now');
            // rating 0.0 to 10.0 with one decimal (nullable sometimes)
            $rating = $faker->optional(0.85)->randomFloat(1, 0, 10); // 85% chance to have rating
            // duration between 70 and 210 minutes (nullable sometimes)
            $duration = $faker->optional(0.9)->numberBetween(70, 210);

            // poster_url use placeholder images (nullable sometimes)
            $posterUrl = $faker->optional(0.8)->imageUrl(300, 450, 'movies', true, 'Faker');

            // sometimes no director (nullable)
            $directorId = $faker->optional(0.85)->randomElement($directorIds);

            $movies[] = [
                'name' => ucfirst($faker->words($nb = $faker->numberBetween(1,4), $asText = true)),
                'year' => $year,
                'description' => $faker->paragraphs($nb = 3, $asText = true),
                'duration' => $duration,
                'rating' => $rating !== null ? number_format((float)$rating, 1, '.', '') : null,
                'poster_url' => $posterUrl,
                'director_id' => $directorId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('movies')->insert($movies);
    }
}
