@extends('layouts.app')

@section('content')

	<div class="container-fluid">

		<div class="pending-requests-section">
			<h3 class="h5">Pending Requests</h3>
			<ul class="list-group">
				@foreach($friendRequests as $friend)
					<li class="list-group-item d-flex justify-content-between align-items-center">
						{{ $friend->user_id }}&nbsp;&nbsp;
						<a href="friends/approve/{{$friend->user_id}}" class="approve-friend btn btn-sm btn-secondary">Approve</a>
					</li>
				@endforeach
			</ul>
		</div>

		<div class="approved-friends-section mt-5">
			<h3 class="h5">Friendships</h3>
			<ul id="friends-results" class="list-group">

				@foreach($friends as $friend)
					<li class="list-group-item d-flex justify-content-between align-items-center">
						{{ $friend->friend_id }}&nbsp;&nbsp;
					</li>
				@endforeach
			</ul>
		</div>

	</div> <!-- /.container-fluid -->-
@endsection