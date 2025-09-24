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
}
