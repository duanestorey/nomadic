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

    // Return distance between us and someone else
    public function distanceFrom( $location ) {
        $radius_earth = 6371; // kms
        $dlat = deg2rad( $location->latitude -  $this->latitude );
        $dlon = deg2rad( $location->longitude - $this->longitude );

        $a = sin( $dlat/2 ) * sin( $dlat/2 ) + cos( deg2rad( $location->latitude ) ) * cos( deg2rad( $this->latitude ) ) * sin( $dlon/2 ) * sin( $dlon/2 );
        $c = 2*atan2( sqrt( $a ) , sqrt( 1 - $a ) );

        return round( $radius_earth * $c );
    }
}
