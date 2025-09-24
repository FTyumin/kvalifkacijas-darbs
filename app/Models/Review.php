<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
        'title',
        'description',
    ];

    public function user() {
        return $this->hasMany(User::class);
    }

    public function movie() {
        return $this->hasMany(Movie::class);
    }
    
}
