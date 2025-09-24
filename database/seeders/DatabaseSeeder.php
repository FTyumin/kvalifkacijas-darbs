<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            // DirectorSeeder::class,
            // ActorSeeder::class,
            // MovieSeeder::class,
            // ActorMovieSeeder::class,
            KnownMovieSeeder::class
        ]);

    }
}
