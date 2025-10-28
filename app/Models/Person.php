<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Person extends Model
{
    protected $fillable = [
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

    public function movies() {
        return $this->BelongsToMany(Movie::class);
    }
}
