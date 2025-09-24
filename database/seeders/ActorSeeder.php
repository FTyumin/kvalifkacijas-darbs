<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ActorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $actors = [];

        // Create 40 actors
        for ($i = 0; $i < 40; $i++) {
            // birth year between 1940 and 2005
            $birthYear = $faker->numberBetween(1940, 2005);
            // random birth_date or null sometimes
            $birthDate = $faker->optional(0.7)->date('Y-m-d'); // 70% chance to have exact date

            $actors[] = [
                'name' => $faker->name,
                'nationality' => $faker->optional(0.8)->country,
                'birth_year' => $birthYear,
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'birth_date' => $birthDate,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('actors')->insert($actors);
    }
}
