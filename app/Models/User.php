<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Maize\Markable\Markable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Markable;
     protected static $markableTable = 'markables';


    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static $marks = [
        Seen::class,
        WantToWatch::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function wantToWatch()
    {
        return $this->belongsToMany(Movie::class, 'markable_watchlist', 'user_id', 'markable_id');
    }

    public function ratedMovies()
    {
        return $this->belongsToMany(Movie::class, 'reviews')
            ->withPivot('rating', 'created_at');
    }

    public function seenMovies() {
        return $this->belongsToMany(Movie::class, 'markable_seen', 'user_id', 'markable_id');
    }

    public function favorites() {
        return $this->belongsToMany(Movie::class, 'markable_favorites', 'user_id', 'markable_id');
    }

    /**
     * Get user's favorite movies (rating >= 4)
     */
    // public function favoriteMovies()
    // {
    //     return $this->belongsToMany(Movie::class, 'reviews')
    //         ->withPivot('rating', 'created_at')
    //         ->wherePivot('rating', '>=', 4);
    // }



    public function lists()
    {
        return $this->hasMany(MovieList::class);
    }

    public function favoriteGenres() {
        return $this->belongsToMany(Genre::class, 'user_favorite_genres')
            ->withTimestamps();
    }

    public function favoriteActors() {
        return $this->belongsToMany(Person::class, 'user_favorite_actors')
            ->withTimestamps();
    }

    public function getRedirectRoute()
    {
        if (! $this->quiz_completed) {
            return route('quiz.show'); 
        }
        return view('quiz.show');
    }
  
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    public function followers()
    {
        return $this->hasMany(UserRelationship::class, 'followee_id');
    }

    // define relationship with UserRelationship model for followees

    public function followees()
    {
        return $this->hasMany(UserRelationship::class, 'follower_id');
    }
}
