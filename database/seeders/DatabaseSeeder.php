<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            DirectorSeeder::class,
            ActorSeeder::class,
            MovieSeeder::class,
            ActorMovieSeeder::class,
            GenreSeeder::class,
            GenreMovieSeeder::class
        ]);
        // KnownMovieSeeder::class,

        DB::table('users')->insert([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

    }
}
