@extends('layouts.main')

@section('content')
	<h1>Post Question</h1>
	<form class="ui form" method="POST" action="{{ url('/ticket/create') }}">
		<div class="ui segment">
            <div class="ui error message"></div>
			{{csrf_field()}}
			<div class="field">
				<label>Title</label>
				<input type="text" name="title" placeholder="Title">
			</div>

			<div class="two fields" style="display: none">
				<div class="field">
					<label>Category</label>
					<div class="ui fluid search normal selection dropdown" id="for_category">
						<input type="hidden" name="category">
						<i class="dropdown icon"></i>
						<div class="default text">Category</div>
						<div class="menu">
							<div class="item" data-value="None">None</div>
							<div class="item" data-value="Question">Question</div>
							<div class="item" data-value="Incident">Incident</div>
							<div class="item" data-value="Problem">Problem</div>
						</div>
					</div>
				</div>
				<div class="field">
					<label>Priority</label>
					<div class="ui fluid search normal selection dropdown" id="for_priority">
						<input type="hidden" name="priority">
						<i class="dropdown icon"></i>
						<div class="default text">Priority</div>
						<div class="menu">
							<div class="item" data-value="Low">Low</div>
							<div class="item" data-value="Medium">Medium</div>
							<div class="item" data-value="High">High</div>
							<div class="item" data-value="Urgent">Urgent</div>
						</div>
					</div>
				</div>
			</div>

			<div class="field">
				<label>Body</label>
				<textarea id="summernote" name="description"></textarea>
			</div>


			<div class="two fields">
				<div class="field" style="display: none">
					<label>Assign To Doctor (Optional)</label>
					<div class="ui fluid search normal selection dropdown" id="for_assignto">
						<input type="hidden" name="assign_to">
						<i class="dropdown icon"></i>
						<div class="default text">Assign To</div>
						<div class="menu">
							
						</div>
					</div>
				</div>
				<div class="field">
					<label>Created By</label>
					<input type="text" name="created_by" placeholder="Created By" value="{{Auth::user()->username}}" readonly>
				</div>
			</div>

			<button class="ui teal button"> Post Question </button>
		</div>
	</form>

	@if($errors->any())
	<div class="segment">
		<div class="ui error message">
			<ul>
			@foreach($errors->all() as $error)
				<li>{{$error}}</li>
			@endforeach
			</ul>
		</div>
	</div>
	@endif
@endsection


@section('js')
	<script src="{{ asset('js/ticket_create.js') }}"></script>
@endsection