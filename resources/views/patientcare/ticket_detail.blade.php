@extends('layouts.main')

@section('content')
@include('layouts.ticketFilter')

<h4 class="ui horizontal divider header">Question Detail</h4>
<input type="hidden" id="scroll_btm" value="@if(session()->has('data')){{session('data')}}@endif">
<div class="ui segments">
	<div class="ui secondary clearing segment">
		<h3 class="ui header" style="margin-bottom: 10px"><span style="font-size: small;"></span> {{$ticket->title}}</h3>
		<h5 class="ui header" style="margin-top: 0px">
			<div class="ui left floated" >
				<span class="sub header" style="color: #f2711c!important;">
					<i class="user orange circle icon"></i>{{\DB::table('sysdb.users')->where('username','=',$ticket->created_by)->first()->username}}
				</span>
			</div>
			<div class="ui right floated" >
				<span class="sub header">{{Carbon\Carbon::parse($ticket->created_at)->toDayDateTimeString()}}</span>
			</div>
		</h5>
	</div>
	<div class="ui clearing padded attached segment teal tertiary inverted" style="border-color: rgb(0, 181, 173);">

		<form method="POST" class="ui form" id="messageForm" action="/ticket/{{$ticket->id}}">
			{{csrf_field()}}

    		<input type="hidden" name="_method" value="PUT">
    		<input type="hidden" name="text" id="ticket_{{$ticket->id}}_text">
			<div id="ticket_{{$ticket->id}}">
				{!! nl2br($ticket->description) !!}
				<!-- {!!$ticket->description!!} -->
			</div>
			<div class="ui buttons right floated" style="display: none; margin-top: 10px" id="ticket_{{$ticket->id}}_button">
				<button class="ui button" type="button" data-id="{{$ticket->id}}" data-type="ticket" cancel>Cancel</button>
				<button class="ui teal button" data-id="{{$ticket->id}}" data-type="ticket" save>Update</button>
			</div>
		</form>

	</div>
</div>

<h4 class="ui horizontal divider header">Conversation</h4>

@foreach($ticket->messages()->get() as $message)
	<?php
		$showsegment = ($message->message_type == 'remark' && Auth::user()->groupid == 'patient') ? false : true;
	?>
	@if($showsegment)
	<div class="ui segments" id="segment_{{$message->id}}">
		<div class="ui secondary clearing segment">
			<h5 class="ui header">
				<?php  
					$postedBy = DB::table('sysdb.users')->find($message->user_id)->username;
				?>
				<div class="avatar-circle left floated @if($message->message_type == 'patient'){{'user_color'}}@else{{'admin_color'}}@endif">
					<span class="initials">{{strtoupper($postedBy[0])}}</span>
				</div>
				<div class="content" style="margin-left: 0.5em;">
					Posted By 
					@if($message->message_type != 'patient'){!!'<a>Doctor</a>'!!}@endif 
					@if($message->updflg)<span style="color: red;opacity: 0.5">*Edited Message</span>@endif
					@if($message->message_type == 'remark'){!!'<a>as Remark</a>'!!}@endif
					<span class="sub header">{{$postedBy}}</span>
				</div>
  				<div class="ui right floated" >
					@if(Auth::id() == $message->user_id)
						<button class="ui blue mini basic button right floated" type="button" data-id="{{$message->id}}" data-type="message" edit>Edit</button>
					@endif
					<span class="sub header" >{{Carbon\Carbon::parse($message->created_at)->toDayDateTimeString()}}</span>
  				</div>
			</h5>
		</div>
		<div class="ui clearing @if($message->message_type == 'patient'){{'teal'}}@elseif($message->message_type == 'doctor'){{'orange'}}@else{{'yellow tertiary inverted'}}@endif padded segment" style="color: black !important">

			<form method="POST" class="ui form" id="messageForm" action="{{url('/message/'.$message->id)}}">
				{{csrf_field()}}
    			<input type="hidden" name="_method" value="PUT">
    			<input type="hidden" name="text" id="message_{{$message->id}}_text">
		  		<div id="message_{{$message->id}}">{!!$message->text!!}</div>
		  		<div class="ui buttons right floated" style="display: none; margin-top: 10px" id="message_{{$message->id}}_button">
					<button class="ui button" type="button" data-id="{{$message->id}}" data-type="message" cancel>Cancel</button>
					<button class="ui teal button" data-id="{{$message->id}}" data-type="message" save>Update</button>
				</div>
			</form>

		</div>
	</div>
	@endif

@endforeach

	<form method="POST" class="ui form" id="messageForm" action="{{ url('/message') }}" id="submit_form">
		{{csrf_field()}}
        <div class="ui error message"></div>
		<input type="hidden" name="status" value="normal">
		<input type="hidden" name="ticket_id" value="{{$ticket->id}}">
		<div class="ui segments clearing" id="newMessage">
			<div class="ui segment">
				<textarea id="summernote" name="message"></textarea>
			</div>
			<div class="ui segment">
				<div class="fields">
					<div class="field">
						<button class="ui teal button" type="submit" id="submit_message">Submit Message</button>
					</div>
				</div>
			</div>
		</div>
	</form>

	@if($errors->any())
	<div class="ui centered grid">
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
	<script src="{{ asset('js/ticket.js') }}"></script>
@endsection