<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Movie;

class KnownMovieSeeder extends Seeder
{
    public function run()
    {

        // Insert directors and keep IDs
        $directors = [
            ['name' => 'Christopher Nolan', 'nationality' => 'British-American', 'birth_year' => '1970', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Francis Ford Coppola', 'nationality' => 'American', 'birth_year' => '1939', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lana Wachowski & Lilly Wachowski', 'nationality' => 'American', 'birth_year' => '1965/1967', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bong Joon-ho', 'nationality' => 'South Korean', 'birth_year' => '1969', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quentin Tarantino', 'nationality' => 'American', 'birth_year' => '1963', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Steven Spielberg', 'nationality' => 'American', 'birth_year' => '1946', 'created_at' => now(), 'updated_at' => now()],
        ];

        $directorIds = [];
        foreach ($directors as $d) {
            $directorIds[] = DB::table('directors')->insertGetId($d);
        }

        // Insert actors and keep IDs
        $actors = [
            ['name' => 'Leonardo DiCaprio', 'nationality' => 'American', 'birth_year' => '1974', 'gender' => 'male', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Joseph Gordon-Levitt', 'nationality' => 'American', 'birth_year' => '1981', 'gender' => 'male',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ellen Page', 'nationality' => 'Canadian', 'birth_year' => '1987', 'gender' => 'female',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marlon Brando', 'nationality' => 'American', 'birth_year' => '1924', 'gender' => 'male',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Pacino', 'nationality' => 'American', 'birth_year' => '1940', 'gender' => 'male', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keanu Reeves', 'nationality' => 'Canadian', 'birth_year' => '1964', 'gender' => 'male',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Carrie-Anne Moss', 'nationality' => 'Canadian', 'birth_year' => '1967', 'gender' => 'female',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Song Kang-ho', 'nationality' => 'South Korean', 'birth_year' => '1967', 'gender' => 'male',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Choi Woo-shik', 'nationality' => 'South Korean', 'birth_year' => '1990', 'gender' => 'male',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Uma Thurman', 'nationality' => 'American', 'birth_year' => '1970', 'gender' => 'female',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Samuel L. Jackson', 'nationality' => 'American', 'birth_year' => '1948', 'gender' => 'male',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tom Hanks', 'nationality' => 'American', 'birth_year' => '1956', 'gender' => 'male',  'created_at' => now(), 'updated_at' => now()],
        ];

        $actorIds = [];
        foreach ($actors as $a) {
            $actorIds[] = DB::table('actors')->insertGetId($a);
        }

        // Helper to find actor ID by name quickly (simple mapping - adjust if you change ordering)
        $actorByName = [];
        foreach ($actorIds as $i => $id) {
            $actorByName[$actors[$i]['name']] = $id;
        }

        // Insert movies (with director_id assigned from directorIds array above)
        // We'll insert 6 well-known movies
        $movies = [
            [
                'name' => 'Inception',
                'year' => 2010,
                'description' => 'A skilled thief leads a team into people\'s dreams to steal and implant ideas.',
                'duration' => 148,
                'rating' => 8.8,
                'poster_url' => 'https://example.com/posters/inception.jpg',
                'director_id' => $directorIds[0], // Christopher Nolan
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The Godfather',
                'year' => 1972,
                'description' => 'The aging patriarch of an organized crime dynasty transfers control to his reluctant son.',
                'duration' => 175,
                'rating' => 9.2,
                'poster_url' => 'https://example.com/posters/godfather.jpg',
                'director_id' => $directorIds[1], // Coppola
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The Matrix',
                'year' => 1999,
                'description' => 'A hacker discovers reality is a simulation and joins the rebellion.',
                'duration' => 136,
                'rating' => 8.7,
                'poster_url' => 'https://example.com/posters/matrix.jpg',
                'director_id' => $directorIds[2], // Wachowskis
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Parasite',
                'year' => 2019,
                'description' => 'A poor family schemes to become employed by a wealthy household with dark consequences.',
                'duration' => 132,
                'rating' => 8.6,
                'poster_url' => 'https://example.com/posters/parasite.jpg',
                'director_id' => $directorIds[3], // Bong Joon-ho
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pulp Fiction',
                'year' => 1994,
                'description' => 'Interconnected stories of crime, redemption, and dark humor in Los Angeles.',
                'duration' => 154,
                'rating' => 8.9,
                'poster_url' => 'https://example.com/posters/pulpfiction.jpg',
                'director_id' => $directorIds[4], // Quentin Tarantino
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Saving Private Ryan',
                'year' => 1998,
                'description' => 'During WWII, soldiers search for a paratrooper whose brothers were killed in action.',
                'duration' => 169,
                'rating' => 8.6,
                'poster_url' => 'https://example.com/posters/savingprivateryan.jpg',
                'director_id' => $directorIds[5], // Spielberg
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $movieIds = [];
        foreach ($movies as $m) {
            // $movieIds[] = DB::table('movies')->insertGetId($m);
            Movie::create($m);
            $movie = Movie::where('name', $m['name'])->first();
            $movieIds[] = $movie->id;
        }

        // Map movies by name to their IDs
        $movieByName = [];
        foreach ($movieIds as $i => $id) {
            $movieByName[$movies[$i]['name']] = $id;
        }

        // Attach known actors to the appropriate movies in the pivot table
        $pivot = [
            // Inception: Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page
            ['movie' => 'Inception', 'actors' => ['Leonardo DiCaprio', 'Joseph Gordon-Levitt', 'Ellen Page']],

            // The Godfather: Marlon Brando, Al Pacino
            ['movie' => 'The Godfather', 'actors' => ['Marlon Brando', 'Al Pacino']],

            // The Matrix: Keanu Reeves, Carrie-Anne Moss
            ['movie' => 'The Matrix', 'actors' => ['Keanu Reeves', 'Carrie-Anne Moss']],

            // Parasite: Song Kang-ho, Choi Woo-shik
            ['movie' => 'Parasite', 'actors' => ['Song Kang-ho', 'Choi Woo-shik']],

            // Pulp Fiction: Uma Thurman, Samuel L. Jackson, John Travolta (John Travolta not in list; we'll attach Samuel & Uma)
            ['movie' => 'Pulp Fiction', 'actors' => ['Uma Thurman', 'Samuel L. Jackson']],

            // Saving Private Ryan: Tom Hanks
            ['movie' => 'Saving Private Ryan', 'actors' => ['Tom Hanks']],
        ];

        $pivotRows = [];
        foreach ($pivot as $entry) {
            $mid = $movieByName[$entry['movie']];
            foreach ($entry['actors'] as $actorName) {
                if (!isset($actorByName[$actorName])) {
                    // If actor wasn't inserted earlier, skip (or insert them)
                    continue;
                }
                $pivotRows[] = [
                    'actor_id' => $actorByName[$actorName],
                    'movie_id' => $mid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // if (!empty($pivotRows)) {
        //     // chunk insert to be safe
        //     foreach (array_chunk($pivotRows, 200) as $chunk) {
        //         DB::table('actor_movie')->insert($chunk);
        //     }
        // }
    }
}
