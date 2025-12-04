<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieList extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
    ];

    protected $table = 'lists';

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function movies() {
        return $this->belongsToMany(
        \App\Models\Movie::class, 
        'movie_lists',           
        'list_id',                
        'movie_id'                
        )
        ->withTimestamps()
        ->withPivot('position');
    }
    
    public function addMovie(int $movieId, ?int $position = null) {
        // Avoid duplicates
        if ($this->movies()->where('movie_id', $movieId)->exists()) {
            return; 
        }

        if (is_null($position)) {
            $position = $this->movies()->count() + 1;
        }

        $this->movies()->attach($movieId, ['position' => $position]);
    }

    public function removeMovie(int $movieId) {
        $this->movies()->detach($movieId);
    }

    protected static function booted() {
        static::created(function ($movieList) {
            Activity::create([
                'user_id' => $movieList->user_id,
                'activityable_type' => MovieList::class,
                'activityable_id' => $movieList->id,
            ]);
        });
    }
}
