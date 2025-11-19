<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    protected $fillable = [
        'name'
    ];

    // Relationship with Movies (Many-to-Many)
    public function movies()
    {
        return $this->belongsToMany(Movie::class)->withTimestamps();
    }

    // Accessor for movies count
    public function getMoviesCountAttribute()
    {
        return $this->movies()->count();
    }

    public function users() 
    {
        return $this->hasMany(User::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorite_genres')
            ->withTimestamps();
    }
}
