<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class TmdbApiClient {
    protected Client $http;
    protected string $base;
    protected ?string $bearer;
    protected ?string $apiKey;

    public function __construct() {
        $this->base   = rtrim(config('services.tmdb.base', env('TMDB_BASE', 'https://api.themoviedb.org/3')), '/');
        $this->bearer = env('TMDB_BEARER_TOKEN');
        $this->apiKey = env('TMDB_API_KEY');

        $this->http = new Client([
            'base_uri' => $this->base . '/',
            'timeout'  => 10,
        ]);
    }

    /**
     * Get movie details with extras (credits, images, etc).
     *
     * append_to_response supports: credits,images,external_ids,releases,...
     * see TMDb docs for available append_to_response values. :contentReference[oaicite:2]{index=2}
     *
     * @return array|null
     */

    public function getMovieWithExtras(int $movieId, array $append = ['credits', 'images']) {
        $query = [];
        if ($this->apiKey && empty($this->bearer)) {
            $query['api_key'] = $this->apiKey;
        }
        if (!empty($append)) {
            $query['append_to_response'] = implode(',', $append);
        }

        $options = [
            'query' => $query
        ];

        if ($this->bearer) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $this->bearer,
                'Accept'        => 'application/json',
            ];
        }

        try {
            $res = $this->http->get("movie/{$movieId}", $options);
            $data = json_decode((string) $res->getBody(), true);
            return $data;
        } catch (GuzzleException $e) {
            \Log::warning('Api request failed');
            return null;
        }
    }

    public function trailerKey(int $movieId) {
        $query = [];
        if ($this->apiKey && empty($this->bearer)) {
            $query['api_key'] = $this->apiKey;
        }
        if (!empty($append)) {
            $query['append_to_response'] = implode(',', $append);
        }

        $options = [
            'query' => $query
        ];

        if ($this->bearer) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $this->bearer,
                'Accept'        => 'application/json',
            ];
        }

        try {
            $res = $this->http->get("movie/{$movieId}/videos", $options);
            $data = json_decode($res->getBody(), true);

            $videos = $data['results'] ?? [];

            $trailers = array_filter($videos, fn($v) => $v['type'] === 'Trailer');

            $trailer = reset($trailers);
            $trailer_key = $trailer['key'] ?? null;
            return $trailer_key;
        } catch (GuzzleException $e) {
            \Log::warning('Api request failed');
            return null;
        }
    }
    
    public function PosterUrl(?string $path, string $size = 'w500'): ?string {
        if (empty($path)) return null;
        
        return "https://image.tmdb.org/t/p/{$size}{$path}";
    }

    public function personData(int $id) {
        $query['api_key'] = $this->apiKey;
        $options = ['query' => $query];
        
        $res = $this->http->get("person/{$id}", $options);
        $data = json_decode((string) $res->getBody(), true);
        return $data;
    }
    
    public function getTopMovies(int $limit = 50, array $opts = []): array {
        $method = $opts['method'] ?? 'discover';
        $pageSize = $opts['page_size'] ?? 20;
        $collected = [];
        $page = 1;
        $maxPages = 1000;
        
        $discoverDefaults = [
            'sort_by' =>  'vote_average.desc',
            'vote_count.gte' => $opts['vote_count.gte'] ?? 1000,
            'language' => 'en-US',
            'include_adult' => true,
            'without_genres' => 10402, 10749,99,5
        ];
        
        while(count($collected) < $limit && $page <=$maxPages) {
            $query = ['page' => $page];
            
            if($method === 'popular') {
                $endpoint = 'movie/popular';
            } else if($method === 'top-rated') {
                $endpoint = 'movie/top_rated';
            } else {
                $endpoint = 'discover/movie';
                $query = array_merge($query, array_filter($discoverDefaults, fn($v) => $v !== null));
                
                foreach ($opts as $k => $v) {
                    if (!in_array($k, ['method', 'page_size'])) {
                        $query[$k] = $v;
                    }
                }
            }

            if ($this->apiKey && empty($this->bearer)) {
                $query['api_key'] = $this->apiKey;
            }
            
            $options = ['query' => $query];
            if($this->bearer) {
                $options['headers'] = [
                    'Authorization' => 'Bearer ' . $this->bearer,
                    'Accept' => 'application/json',
                ];
            }
            
            try {
                $res = $this->http->get($endpoint, $options);
                $data = json_decode((string) $res->getBody(), true);
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                \Log::error("TMDb getTopMovies failed on page {$page}: " . $e->getMessage());
                break;
            }
            $results = $data['results'] ?? [];
            if (empty($results)) break;
            
            foreach ($results as $r) {
                if($r['release_date'] > '1970-01-01') {
                    $collected[] = $r;
                } else {
                    continue;
                }

                if (count($collected) >= $limit) break 2;
            }
            $page++;
        }
        return array_slice($collected, 0, $limit);
    }
}