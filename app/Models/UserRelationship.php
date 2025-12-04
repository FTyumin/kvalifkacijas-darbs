<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRelationship extends Model
{
    protected $fillable = [
        'follower_id',
        'followee_id',
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function followee()
    {
        return $this->belongsTo(User::class, 'followee_id');
    }

    protected static function booted() {
        static::created(function ($userRelationship) {
            Activity::create([
                'user_id' => $userRelationship->followee_id,
                'activityable_type' => userRelationship::class,
                'activityable_id' => $userRelationship->id,
            ]);
        });
    }
}
