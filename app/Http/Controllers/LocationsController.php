<?php

namespace App\Http\Controllers;

use Validator;
use App\Location;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class LocationsController extends Controller
{

	public function index()
	{
		$location = Location::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->take(1)->first();

		return view('home', compact('location'));
	}

	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
            'location' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('home')
                        ->withErrors($validator)
                        ->withInput();
        }

        $cordinates = explode("," , $request->get('location'));

        $geocode = json_decode($this->reverseGeocode($cordinates[0], $cordinates[1]));

        // implement some error handling if no geocoding was found

        $location = (new Location)->create([
        	'latitude' => $cordinates[0],
        	'longitude' => $cordinates[1],
        	'city' => $geocode->address->city,
        	'country' => $geocode->address->country,
        	'note' => null,
        	'user_id' => auth()->user()->id
        ]);

        return back();
	}

	public function geocode(Request $request)
	{

		$q = $request->get('q');

		$response = (new Client())->request('GET', 'http://photon.komoot.de/api',  [
		    'query' => [
		        'q' => $q,
		        'osm_tag' => 'place:city'
		    ]
		]);

		$data = $response->getBody();

		return json_decode($data)->features;
	}

	public function reverseGeocode($lat, $lon)
	{
		$response = (new Client())->request('GET', 'https://nominatim.openstreetmap.org/reverse',  [
		    'query' => [
		        'format' => 'json',
		        'lat' => $lat,
		        'lon' => $lon,
		        'zoom' => 10,
		        'addressdetails' => 1
		    ]
		]);

		return $response->getBody();
	}
}
