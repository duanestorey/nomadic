<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
	Route::get('get-location', function () {
		$geo = geoip()->getLocation(Request::ip());
		
	    return response()->json([
		    'lat' => $geo->lat,
			'lon' => $geo->lon,
			'city' => $geo->city,
			'country' => $geo->country,
		]);
	});

	Route::get('geocode', 'LocationsController@geocode');
	Route::get('friends', 'FriendsController@index');
	Route::get('friends/search', 'FriendsController@search');
	Route::get('friends/approve/{id}', 'FriendsController@approve');
	Route::post('friends', 'FriendsController@request');
	Route::post('location', 'LocationsController@store');
	Route::get('/', function() { return redirect('home'); } );
	Route::get('/home', 'LocationsController@index');
});
Auth::routes();

Route::get('/home', 'LocationsController@index')->name('home')->middleware('auth');
