@extends('layouts.main')


@section('_style')
		.description{
			 text-align:center;
		}
@endsection

@section('content')
<h1 class="ui center aligned icon header">Welcome back {{Auth::user()->username}}</h1>

<h4 class="ui horizontal divider header">Dashboard List</h4>

<div class="ui cards">

	@if(Auth::user()->type == 'doctor')

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?assign_to={{Auth::id()}}&priority=Urgent&status=Open%2CAnswered">
					<div class="ui red statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$attention}}
						</div>
						<div class="label">Question</div>
					</div>
				</a>
			</div>
			<div class="description">Urgent question need your attention</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?assign_to={{Auth::id()}}&status=Open%2CAnswered">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$open}}
						</div>
						<div class="label">Question</div>
					</div>
				</a>
			</div>
			<div class="description">Open and Answered question for you</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?assign_to={{Auth::id()}}">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$assign}}
						</div>
						<div class="label">Question</div>
					</div>
				</a>
			</div>
			<div class="description">Question assigned to you</div>
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
						<div class="label">Question</div>
					</div>
				</a>
			</div>
			<div class="description">Question replied by you</div>
		</div>
	</div>

	@endif

	@if(Auth::user()->type == 'patient')


	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?report_by={{Auth::id()}}">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$report_by}}
						</div>
						<div class="label">Question</div>
					</div>
				</a>
			</div>
			<div class="description">Question Created by you</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?report_by={{Auth::id()}}&status=Answered">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$answered}}
						</div>
						<div class="label">Question</div>
					</div>
				</a>
			</div>
			<div class="description">Answered Question for you</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?report_by={{Auth::id()}}&status=Open">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$open}}
						</div>
						<div class="label">Question</div>
					</div>
				</a>
			</div>
			<div class="description">Open Question</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?report_by={{Auth::id()}}&status=Resolved">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$resolved}}
						</div>
						<div class="label">Question</div>
					</div>
				</a>
			</div>
			<div class="description">Resolved Question</div>
		</div>
	</div>

	<div class="card">
		<div class="content">
			<div class="ui center aligned header">
				<a class="ui button center" href="/ticket?report_by={{Auth::id()}}&status=Closed">
					<div class="ui statistic">
						<div class="value">
	      					<i class="ticket icon"></i>{{$closed}}
						</div>
						<div class="label">Tickets</div>
					</div>
				</a>
			</div>
			<div class="description">Closed ticket</div>
		</div>
	</div>
	@endif

</div>
	
@endsection


@section('js')
	<!-- <script src="{{ asset('js/ticket.js') }}"></script> -->
@endsection