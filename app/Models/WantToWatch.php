<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\Markable\Mark;

class WantToWatch extends Mark
{
    public static function markableRelationName(): string
    {
        return 'interestedUsers'; 
    }
    
    public static function markRelationName(): string
    {
        return 'wantToWatch'; 
    }

    public function movie() {
        return $this->belongsTo(Movie::class, 'markable_id');
    }

    protected $table = 'markable_watchlist';
}
