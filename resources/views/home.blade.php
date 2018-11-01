@extends('layouts.app')

@section('content')

	<div class="container-fluid current-location">
		<form action="/location" method="post">

			@csrf

			<div class="form-row">
				<div class="col">
					@if (empty($location->city))
					<input type="text" name="location" id="location-search-field" class="form-control" placeholder="Where in the world are you right now?" autocomplete="off">
					@else
					<input type="text" name="location" id="location-search-field" class="form-control" placeholder="In {{$location->city}}, {{$location->country}}" autocomplete="off">
					@endif

					<input type="hidden" name="location" id="location-search" />
				</div>

				<div class="col-auto">
					<button href="#" class="btn btn-secondary mb-2 get-location">Detect</button>	
				</div>
					
				<div class="col-auto">
					<button type="submit" class="btn btn-primary mb-2">Update</button>
				</div>
			</div>
		</form>

		<ul id="location-results" class="list-group"></ul>

	    <section id="map"></section>

	</div> <!-- /.container-fluid -->

@endsection

@section('scripts')

	<script>
		var map = L.map('map', {
		    center: [15,0],
		    zoom: 2,
		    fitWorld: true,
		    minZoom: 2,
		    noWrap: true,
		    worldCopyJump: true,
		    maxBounds: [
		        [-90, -180],
		        [90, 180]
		    ]
		});

		@if($location)
			L.marker([{{ $location->lat }}, {{ $location->lon }}], {})
			 .addTo(map)
			 .bindPopup('{{ $location->user->name }} is currently in {{ $location->city }} {{ $location->country }}');
		@endif

		@if($friends)
			@foreach($friends as $friend)
				L.marker([{{ $friend->lat }}, {{ $friend->lon }}], {})
				 .addTo(map)
				 .bindPopup('{{ $friend->name }} is currently in {{ $friend->city }} {{ $friend->country }}');
			@endforeach
		@endif

		L.tileLayer('https://c.tile.openstreetmap.org/{z}/{x}/{y}.png ', {
		    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		$(document).ready(function() {
			$('.get-location').on('click', function(e) {
				e.preventDefault();

				$.get('/get-location', function( data ) {
					$('#location-search').val( data.lat + ',' + data.lon );
					$('#location-search-field').val( data.city + ', ' + data.country );
					//$('.get-location').html('Update my location');
					L.marker([data.lat, data.lon], {}).addTo(map).bindPopup('');
				});
			})

			$('#location-search-field').on('keyup', function(){
				$('#location-results').empty();

				$.get('/geocode/?q='+$(this).val(), function( data ) {

				    $.each( data , function( key, value ){					
					     $('#location-results').append('<li class="location list-group-item" data-location-city="'+value.properties.name+'" data-location-country="'+value.properties.country+'" data-location-coordinates="'+value.geometry.coordinates[1]+','+ value.geometry.coordinates[0] +'">'+value.properties.name + ', ' + value.properties.country +'</li>');
					});

				});
			});

			$('#location-results').on('click', '.location' ,function(e) {
				populateLocationForm(this);
			});

		})

		function populateLocationForm(el)
		{
			$('#location-search').val($(el).attr('data-location-coordinates'));
			$('#location-search-field').val($(el).attr('data-location-city') + ', ' + $(el).attr('data-location-country') );
			$('#location-search-coordinates').val($(el).attr('data-location-coordinates'));
			$('#request-friendship input[name="email"]').val($(el).attr('data-request-id'));

			$('#location-results').empty();
		}		
		

	</script>
@endsection