<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\Markable\Mark;
use App\Models\Movie;

class Seen extends Mark
{
    public static function markableRelationName(): string
    {
        return 'watchers';
    }
    
    public static function markRelationName(): string
    {
        return 'viewingHistory';
    }

    public function movie() {
        return $this->belongsTo(Movie::class, 'markable_id');
    }

    protected $table = 'markable_seen';
}
