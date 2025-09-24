<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ActorMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $actorIds = DB::table('actors')->pluck('id')->toArray();
        $movieIds = DB::table('movies')->pluck('id')->toArray();

        $pivotRows = [];

        foreach ($movieIds as $movieId) {
            // attach between 2 and 8 actors per movie
            $numActors = $faker->numberBetween(2, min(8, count($actorIds)));
            // pick unique actors for this movie
            $selected = $faker->randomElements($actorIds, $numActors);

            foreach ($selected as $actorId) {
                $pivotRows[] = [
                    'actor_id' => $actorId,
                    'movie_id' => $movieId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // To avoid duplicate unique constraint issues, we can chunk insert
        $chunks = array_chunk($pivotRows, 500);
        foreach ($chunks as $chunk) {
            DB::table('actor_movies')->insert($chunk);
        }
    }
}
