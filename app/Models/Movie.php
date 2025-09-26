<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Actor;

class Movie extends Model
{
     protected $fillable = [
        'name',
        'year',
        'description',
        'duration',
        'rating',
        'poster_url',
        'director_id'
    ];

    protected $casts = [
        'year' => 'integer',
        'duration' => 'integer',
        'rating' => 'decimal:1',
    ];

    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class)
                    ->withTimestamps();
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeSearchByNameOrDirector($query, $search)
    {
        return $query->leftJoin('directors', 'movies.director_id', '=', 'directors.id')
            ->whereRaw('LOWER(movies.name) LIKE ? OR LOWER(directors.name) LIKE ?', [
            '%' . strtolower($search) . '%',
            '%' . strtolower($search) . '%'
            ])->select('movies.*'); // Only select movie columns to avoid conflicts
    }
}
