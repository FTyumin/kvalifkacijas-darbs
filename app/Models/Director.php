<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $fillable = [
        'name',
        'nationality',
        'birth_year',
        'birth_date'
    ];

    protected $casts = [
        'birth_year' => 'integer',
        'birth_date' => 'date',
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    // Accessor for age
    public function getAgeAttribute()
    {
        return $this->birth_date ? now()->diffInYears($this->birth_date) : null;
    }

    // Accessor for movies count
    public function getMoviesCountAttribute()
    {
        return $this->movies()->count();
    }
}
