<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
