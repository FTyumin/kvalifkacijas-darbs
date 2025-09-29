<?php

namespace App\Models;

use App\Models\Actor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Maize\Markable\Markable;
use Maize\Markable\Models\Favorite;
use Maize\Markable\Models\Bookmark;


class Movie extends Model
{
    use HasFactory, Markable;

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

    protected static $marks = [
        Favorite::class,
        Bookmark::class,
    ];

    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class)
                    ->withTimestamps();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class)
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function updateRating() {
        $id = $this->id;
        $reviews = $this->reviews->pluck('rating')->toArray();

        $rating = array_sum($reviews) / count($reviews);

        $this->update(['rating' => $rating]);
    }
}
