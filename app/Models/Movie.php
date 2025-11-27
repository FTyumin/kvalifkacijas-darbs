<?php

namespace App\Models;

// use App\Models\Actor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Maize\Markable\Markable;
use Maize\Markable\Models\Favorite;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Seen;
use App\Models\WantToWatch;


class Movie extends Model
{
    use HasFactory, Markable, HasSlug;

    public $incrementing = false;
    protected $keyType = 'int';
    protected static $markableTable = 'markables';

    protected $fillable = [
        'id',
        'name',
        'year',
        'description',
        'duration',
        'rating',
        'poster_url',
        'director_id',
        'language',
        'tmdb_rating',
    ];

    protected $casts = [
        'year' => 'integer',
        'duration' => 'integer',
        'rating' => 'decimal:1',
    ];

    protected static $marks = [
        Favorite::class,
        Seen::class,
        WantToWatch::class,
    ];

    public function getSlugOptions() : SlugOptions 
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function director()
    {
        return $this->belongsTo(Person::class, 'director_id');
    }

    public function actors()
    {
        return $this->belongsToMany(Person::class, 'actor_movie', 'movie_id', 'actor_id')->withTimestamps();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class)->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function lists()
    {
        return $this->belongsToMany(MovieList::class, 'movie_lists')
                    ->withTimestamps()
                    ->withPivot('position');
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

    // relationships for marking
    public function interestedUsers()
    {
        return $this->markableRelation(WantToWatch::class);
    }
    
    public function viewers()
    {
        return $this->markableRelation(Seen::class);
    }
    
    public function fans()
    {
        return $this->markableRelation(Favorite::class);
    }
}
