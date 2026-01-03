<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();

        // DB::table('users')->insert([
        //     'name' => 'test',
        //     'email' => 'feodorstjumins@gmail.com',
        //     'password' => Hash::make('password'),
        // ]);

        // DB::table('users')->insert([
        //     'name' => 'admin',
        //     'email' => 'feodor.tjumin28@gmail.com',
        //     'is_admin' => 1,
        //     'password' => Hash::make('password'),
        // ]);

        // DB::table('users')->insert([
        //     'name' => 'demo',
        //     'email' => 'demo@example.com',
        //     'password' => Hash::make('password'),
        // ]);

        // DB::table('users')->insert([
        //     'name' => 'reviewer',
        //     'email' => 'reviewer@example.com',
        //     'password' => Hash::make('password'),
        // ]);

        $users = User::all();
        $movies = Movie::all();

        foreach ($users as $user) {
            $reviewCount = min($movies->count(), $faker->numberBetween(2, 4));
            $pickedMovies = $movies->shuffle()->take($reviewCount);

            foreach ($pickedMovies as $movie) {
                Review::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'movie_id' => $movie->id,
                    ],
                    [
                        'title' => $faker->sentence(4),
                        'description' => $faker->paragraph(3),
                        'rating' => $faker->numberBetween(1, 5),
                        'spoilers' => $faker->boolean(15),
                    ]
                );
            }
        }

        $reviews = Review::all();

        foreach ($reviews as $review) {
            $commentCount = $faker->numberBetween(1, 3);

            for ($i = 0; $i < $commentCount; $i++) {
                $commenter = $users->where('id', '!=', $review->user_id)->random();
                Comment::create([
                    'user_id' => $commenter->id,
                    'review_id' => $review->id,
                    'description' => $faker->sentence(12),
                ]);
            }
        }
    }
}
