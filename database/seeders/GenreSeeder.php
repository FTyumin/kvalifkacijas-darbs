<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    public function run()
    {
        // A small canonical list of genres
        $genres = [
            ['name' => 'Action', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Adventure', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Animation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Comedy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Crime', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Drama', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fantasy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Historical', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Horror', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mystery', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Romance', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sci-Fi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thriller', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'War', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Documentary', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Insert all genres (assumes fresh DB or duplicates OK).
        DB::table('genres')->insert($genres);
    }
}
