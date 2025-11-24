<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\Markable\Mark;

class WantToWatch extends Mark
{
    public static function markableRelationName(): string
    {
        return 'interestedUsers'; // or 'watchers'
    }
    
    public static function markRelationName(): string
    {
        return 'wantToWatch'; // or 'watchlist'
    }

    protected $table = 'markable_watchlist';
}
