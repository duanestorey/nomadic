<?php

namespace App\Http\Controllers;

use App\User;
use App\Friend;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public function search(Request $request)
    {
    	$q = $request->get('q');
    	$user_id = auth()->user()->id;

    	$people = User::where('name','like', '%'.$q.'%')->where('id', '<>' ,$user_id)->take(5)->get();
		$people->only(['name', 'email']);
    	return json_encode($people->all());
    }

    public function request(Request $request)
    {
    	$email = $request->get('email');
    	$user_id = auth()->user()->id;
    	$friend = User::where('email', $email)->first();
    	$is_friend = Friend::where('user_id', $user_id)->where('friend_id', $friend->id)->get();

   
    		$friendRequest = (new Friend)->create([
    			'user_id' => $user_id,
    			'friend_id' => $friend->id,
    			'is_mutual' => 0
    		]);

    	dd($friendRequest);
    }
}
