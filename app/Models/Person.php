<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;

class Person extends Model
{
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'type',
        'nationality',
        'birth_year',
        'birth_date'
    ];

    protected $casts = [
        'birth_year' => 'integer',
        'birth_date' => 'date',
    ];

    protected $table = 'persons';

    public function movies() {
        return $this->BelongsToMany(Movie::class);
    }
}
