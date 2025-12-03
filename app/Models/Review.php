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

    public function movie() {
        return $this->belongsTo(Movie::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likedBy() {
        return $this->belongsToMany(User::class, 'review_likes');
    }

    protected static function booted() {
        static::created(function ($review) {
            Activity::create([
                'user_id' => $review->user_id,
                'activityable_type' => Review::class,
                'activityable_id' => $review->id,
            ]);
        });
    }
}
