<?php

namespace App\Http\Controllers;

use App\User;
use App\Friend;
use Illuminate\Http\Request;

class FriendsController extends Controller
{

	function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
	}

	public function index()
	{
		$friendRequests =  Friend::where('friend_id', $this->user->id)->where('is_mutual',0)->get();	
		$friends = Friend::where('user_id', $this->user->id)->get();
        $myself = User::where('id', $this->user->id)->first();

		return view('friends', compact('friends','friendRequests','myself'));
	}

    public function search(Request $request)
    {
    	$q = $request->get('q');

    	$people = User::where('name','like', '%'.$q.'%')->where('id', '<>' , $this->user->id )->take(5)->get();
		$people->only(['name', 'email']);

    	return json_encode($people->all());
    }

    public function request(Request $request)
    {
    	$email = $request->get('email');
    	$friend = User::where('email', $email)->first();
    	$is_friend = Friend::where('user_id', $this->user->id)->where('friend_id', $friend->id)->get();

		$friendRequest = (new Friend)->create([
			'user_id' => $this->user->id,
			'friend_id' => $friend->id,
			'is_mutual' => 0
		]);

    	return back();
    }

    public function approve($id)
    {
    	$request = Friend::where('user_id', $id)->where('friend_id', $this->user->id);

    	if ( count($request->get()) <> 1)
    	{
    		return false;
    	}

    	$request->update(['is_mutual' => 1]);

    	$returnRequest = (new Friend)->create([
			'user_id' => $this->user->id,
			'friend_id' => $id,
			'is_mutual' => 1
		]);

		return 'is mutual friend';
    }
}
