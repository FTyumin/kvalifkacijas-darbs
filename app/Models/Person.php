<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Person extends Model
{
    use HasSlug;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'type',
        'nationality',
        'birth_year',
        'birth_date',
        'profile_path',
        'biography',
    ];

    protected $casts = [
        'birth_year' => 'integer',
        'birth_date' => 'date',
    ];

    protected $table = 'persons';

    public function moviesAsActor()
    {
        return $this->belongsToMany(Movie::class, 'actor_movie', 'actor_id', 'movie_id')->withTimestamps();
    }

    public function moviesAsDirector()
    {
        return $this->hasMany(Movie::class, 'director_id');
    }

    public function getSlugOptions() : SlugOptions 
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
