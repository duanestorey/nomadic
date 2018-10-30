@extends('layouts.app')

@section('content')

	<div class="container-fluid">

		<form action="/location" method="post">

			@csrf

			<div class="form-row">
				<div class="col">
					<input type="text" name="location" id="location" class="form-control" placeholder="Where in the world are you right now?">
					<p class="form-text">
						<span id="current-location"></span> 
						<a href="#" class="get-location">Get my location</a>
					</p>
				</div>
				
				<div class="col-auto">
					<button type="submit" class="btn btn-primary mb-2">Submit</button>
				</div>
			</div>
		</form>

		<ul id="result"></ul>
	        
	    <section id="map"></section>

	</div> <!-- /.container-fluid -->

@endsection

@section('scripts')

	<script>
		var map = L.map('map', {
		    center: [15,0],
		    zoom: 3,
		    fitWorld: true,
		    minZoom: 3,
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

		L.tileLayer('https://c.tile.openstreetmap.org/{z}/{x}/{y}.png ', {
		    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		$(document).ready(function() {
			$('#demo').html('test');
		
			$('.get-location').on('click', function(e) {
				e.preventDefault();

				$.get('/get-location', function( data ) {
					$('#location').val( data.lat + ',' + data.lon );
					$('#current-location').html(data.city + ', ' + data.country + '.');
					$('.get-location').html('Update my location');
					L.marker([data.lat, data.lon], {}).addTo(map).bindPopup('');
					console.log(data);
				});
			})


			$('.live-search-list li').each(function(){
				$(this).attr('data-search-term', $(this).text().toLowerCase());
			});

			$('#location').on('keyup', function(){
				$('#result').empty();

				$.get('/geocode/?q='+$(this).val(), function( data ) {

				    $.each( data , function( key, value ){
						
					     $('#result').append('<li>'+value.properties.name + ', ' + value.properties.country +'</li>');

					});

				});
			});

		})

		
		

	</script>

@endsection