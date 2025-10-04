<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\TmdbApiClient;

class InsertData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:insert-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $apiClient;
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $api = new TmdbApiClient;

        $n = 100;
        $data = $api->getTopMovies($n, ['method' => 'top-rated']);
        $movies = collect($data ?? [])->map(function ($r) use ($api) {
            return [
                'name' => $r['title'] ?? null,
                'year' => !empty($r['release_date']) ? substr($r['release_date'],0,4) : null,
                'description' => $r['overview'],
                'poster_url' => $r['poster_path'],
            ];
        })->all();

        DB::table('movies')->insert($movies);

    }
}
