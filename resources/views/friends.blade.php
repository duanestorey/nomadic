@extends('layouts.app')

@section('content')

	<div class="container-fluid">
		<div class="find-friends-section">
			<h3 class="h5">Find Friends</h3>
			<form action="/friends" method="POST" id="request-friendship">
				@csrf
				<input type="text" name="q" id="friend-search" class="form-control" placeholder="Find a friend to share your travel locations with" autocomplete="off">
				<input type="hidden" name="email">
				<ul id="friends-results" class="list-group"></ul>
			</form>
		</div>

		@if(!$friendRequests->isEmpty()) 
		<div class="pending-requests-section mt-3">
			<h3 class="h5">Pending Requests</h3>
						
			<ul class="list-group">
				@foreach($friendRequests as $friend)
					<li class="list-group-item d-flex justify-content-between align-items-center">
						{{ $friend->name($friend->user_id) }}&nbsp;&nbsp;
						<a href="friends/approve/{{$friend->user_id}}" class="approve-friend btn btn-sm btn-secondary">Approve</a>
					</li>
				@endforeach
			</ul>
		</div>
		@endif

		<div class="approved-friends-section mt-3">
			<h3 class="h5">Friends</h3>
			<ul id="friends-results" class="list-group">

				@if($friends->isEmpty()) 
					<p>{{ __( 'You currently do not have any friends')}}</p>
				@else
					@foreach($friends as $friend)
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<span class="name">{{ $friend->name($friend->friend_id) }}&nbsp;&nbsp;</span>

							<?php $location = $friend->getUser()->lastLocation(); ?>
							@if($location)
								<span class="location">{{$location->city}}, {{$location->country}}</span>
								<span class="location"><?php echo $myself->lastLocation()->distanceFrom( $location ); ?> kms</span>
							@else
								
							@endif
						</li>
					@endforeach
				@endif
			</ul>
		</div>
	</div> <!-- /.container-fluid -->-
@endsection

@section('scripts')
	<script>
		$(document).ready(function() {
			$('#friend-search').on('keyup', function(){
				$('#friends-results').empty();

				$.get('/friends/search/?q='+$(this).val(), function( data ) {

				    $.each( JSON.parse(data) , function( key, value ){
					    $('#friends-results').append('<li class="friend list-group-item d-flex justify-content-between align-items-center" data-request-name="'+value.name+'" data-request-id="'+value.email+'">' + value.name + '&nbsp;<a href="#" class="add-friend btn btn-sm btn-secondary">Add friend</a></li>');
					});

				});
			});

			$('#friends-results').on('click', '.friend' ,function(e) {
				populateFriendForm(this);
			});

			$('#friends-results').on('click', '.add-friend' ,function(e) {
				e.preventDefault();
				var el = $(this).parent();
				populateFriendForm(el);
				$('#request-friendship').submit();
			});
		});

		function populateFriendForm(el)
		{
			$('#friend-search').val($(el).attr('data-request-name'));
			$('#request-friendship input[name="email"]').val($(el).attr('data-request-id'));
		}
	</script>
@endsection