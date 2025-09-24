<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Actor extends Model
{
    protected $fillable = [
        'name',
        'nationality',
        'birth_year',
        'gender',
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
