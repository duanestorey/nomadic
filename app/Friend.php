<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
	protected $fillable = [
        'user_id', 
        'friend_id',
        'meeting_point',
        'is_mutual'
    ];
}
