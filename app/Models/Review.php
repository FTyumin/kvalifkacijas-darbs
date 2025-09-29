<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ReviewObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ReviewObserver::class])]
class Review extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
        'title',
        'rating',
        'description',
        'spoilers'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function movies() {
        return $this->belongsTo(Movie::class);
    }
    
}
