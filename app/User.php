<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function locations()
    {
        return $this->hasMany('App\Location');
    }

    public function friends()
    {
    	$sql = "select f1.*
				from friends f1
				inner join friends f2 on f1.user_id = f2.friend_id and f1.friend_id = f2.user_id 
				where f1.user_id = {$this->id}";

    	$friends = DB::select(DB::raw($sql));

    	return $friends;
    }

    public function friendsLocations()
    {

    	$locations = [];
    	$friends = $this->friends();
    	
    	if ( !$friends)
    	{
    		return null;
    	}

    	foreach ($friends as $friend) 
    	{
    		$location = \App\Location::where('user_id', $friend->friend_id)->orderBy('created_at', 'DESC')->take(1)->first();
    		if (!empty($location))
    		{
			    $data = [
			    	'friend_id' => $friend->friend_id,
			    	'name' => $location->user->name,
			    	'lat' => $location->latitude,
			    	'lon' => $location->longitude,
			    	'city' => $location->city,
			    	'country' => $location->country,
			    ];

			    $locations[] = (object) $data;
			}
		} 

		return $locations;
    }
}
