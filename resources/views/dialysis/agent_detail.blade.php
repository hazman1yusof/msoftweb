@extends('layouts.main')

@section('content')
<div class="ui cards">
		<h1 class="ui center aligned icon header">Detail for agent : {{$user->username}}</h1>

	<h4 class="ui horizontal divider header">Dashboard List</h4>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?assign_to={{$user->id}}&priority=Urgent&status=Open%2CAnswered">
					<div class="ui red statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$attention}}
						</div>
						<div class="label">Tickets</div>
					</div>
				</a>
			</div>
			<div class="description">Urgent ticket need your attention</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?assign_to={{$user->id}}&status=Open%2CAnswered">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$open}}
						</div>
						<div class="label">Tickets</div>
					</div>
				</a>
			</div>
			<div class="description">Open and Answered ticket for you</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?assign_to={{$user->id}}">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$assign}}
						</div>
						<div class="label">Tickets</div>
					</div>
				</a>
			</div>
			<div class="description">Ticket assigned to you</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket/answer/{{$user->id}}">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$answer}}
						</div>
						<div class="label">Tickets</div>
					</div>
				</a>
			</div>
			<div class="description">Ticket replied by you</div>
		</div>
	</div>

</div>
	
@endsection


@section('js')
	<!-- <script src="{{ asset('js/ticket.js') }}"></script> -->
@endsection