<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DirectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $directors = [];

        // Create 12 directors
        for ($i = 0; $i < 12; $i++) {
            $birthYear = $faker->year($max = '1995'); // plausible director birth years
            $directors[] = [
                'name' => $faker->name,
                'nationality' => $faker->country,
                'birth_year' => $birthYear,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('directors')->insert($directors);
    }
}
