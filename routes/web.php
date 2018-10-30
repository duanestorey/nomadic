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
Route::post('location', 'LocationsController@store');

Auth::routes();

Route::get('/home', 'LocationsController@index')->name('home');
