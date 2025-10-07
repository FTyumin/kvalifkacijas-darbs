<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieList extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_public',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function movies() {
        return $this->hasMany(Movie::class)
                    ->withPivot('position');
    }


}
