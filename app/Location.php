<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
     protected $fillable = [
        'latitude', 
        'longitude',
        'city',
        'country',
        'note', 
        'user_id', 
        'created_at', 
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getCordinatesAttribute()
    {
    	return "{$this->latitude},{$this->longitude}";
    }

    public function getLatAttribute()
    {
    	return $this->latitude;
    }

    public function getLonAttribute()
    {
    	return $this->longitude;
    }

    public function ofUser()
    {
    	
    }
}
