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

        DB::table('users')->insert([
            'name' => 'test',
            'email' => 'feodorstjumins@gmail.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'feodor.tjumin28@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('password'),
        ]);



    }
}
