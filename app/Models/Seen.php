<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\Markable\Mark;

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

    protected $table = 'markable_seen';
}
