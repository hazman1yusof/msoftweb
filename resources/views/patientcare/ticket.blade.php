@extends('layouts.main')

@section('content')
@include('layouts.ticketFilter')	

	<input type="hidden" id="scroll_btm" value="">
	<input id="url" name="url" type="hidden" value="{{url('')}}">
	<a class="positive ui button" href="/ticket/create">Ask a Question</a>

	<h4 class="ui horizontal divider header">Question List</h4>
	<div class="ui three link stackable cards">
		@foreach ($tickets as $ticket)
			<?php

				switch ($ticket->status) {
					case 'Open':
						$colorcard = 'red';
						break;
					case 'Answered':
						$colorcard = 'red';
						break;
					case 'Resolved':
						$colorcard = 'green';
						break;
					case 'Closed':
						$colorcard = 'green';
						break;
					default:
						$colorcard = 'black';
						break;
				}

				switch ($ticket->priority) {
					case 'Low':
						$colorh4 = 'olive';
						break;
					case 'Medium':
						$colorh4 = 'green';
						break;
					case 'High':
						$colorh4 = 'orange';
						break;
					case 'Urgent':
						$colorh4 = 'red';
						break;
					default:
						$colorh4 = 'black';
						break;
				}

			?>
			<div class="card {{$colorh4}}" data-id="{{$ticket->id}}">
				<div class="content">
					
					<div class="header"><span style="font-size: small;"></span> {{$ticket->title}}</div>
					<div class="description">
						{!!str_limit($ticket->description,200,' ...')!!}
					</div>
				</div>
				
				<div class="extra content">
					<span>
						<i class="user orange circle icon"></i><span style="color: #f2711c!important;">{{$ticket->created_by}}</span>
					</span>
					<span style="float: right">
						<i class="comment icon"></i>{{$ticket->messages()->count()}} Conversation
					</span>
				</div>
			</div>
		@endforeach
	</div>

	<div class="ui basic center aligned segment">
	    <div class="ui pagination menu">
	    	<a href="{{ $tickets->url(1) }}" class="ui {{ ($tickets->currentPage() == 1) ? ' disabled' : '' }} icon item">
	            <i class="angle double left icon"></i>
	        </a>
	        <a href="{{ $tickets->previousPageUrl() }}" class="ui {{ ($tickets->currentPage() == 1) ? ' disabled' : '' }} icon item">
	            <i class="angle left icon"></i>
	        </a>
	        @if ($tickets->currentPage() < $tickets->lastPage() && $tickets->currentPage() != 1)
	        	<?php
	        		$loopUntil=0;
	        		if($tickets->lastPage() >= $tickets->currentPage()+2){
	        			$loopUntil=$tickets->currentPage()+2;
	        		}else{
	        			$loopUntil=$tickets->lastPage();
	        		}
	        	?>
		        @for ($i = $tickets->currentPage()-1; $i <= $loopUntil; $i++)
		            <a href="{{ $tickets->url($i) }}" class="{{ ($tickets->currentPage() == $i) ? ' active' : '' }} item">
		                {{ $i }}
		            </a>
		        @endfor
		    @elseif ($tickets->currentPage() >= $tickets->lastPage())
				<?php
	        		$loopFrom=0;
	        		if($tickets->currentPage()-3 <= 0){
	        			$loopFrom=1;
	        		}else{
	        			$loopFrom=$tickets->currentPage()-3;
	        		}
	        	?>
		    	@for ($i = $loopFrom; $i <= $tickets->currentPage(); $i++)
		            <a href="{{ $tickets->url($i) }}" class="{{ ($tickets->currentPage() == $i) ? ' active' : '' }} item">
		                {{ $i }}
		            </a>
		        @endfor
		    @else

		    	<?php
	        		$loopUntil=0;
	        		if($tickets->lastPage() >= $tickets->currentPage()+3){
	        			$loopUntil=$tickets->currentPage()+3;
	        		}else{
	        			$loopUntil=$tickets->lastPage();
	        		}
	        	?>
		    	@for ($i = $tickets->currentPage(); $i <= $loopUntil; $i++)
		            <a href="{{ $tickets->url($i) }}" class="{{ ($tickets->currentPage() == $i) ? ' active' : '' }} item">
		                {{ $i }}
		            </a>
		        @endfor
		    @endif

	        <a href="{{ $tickets->nextPageUrl() }}" class="ui {{ ($tickets->currentPage() == $tickets->lastPage()) ? ' disabled' : '' }} icon item">
	            <i class="angle right icon"></i>
	        </a>
	        <a href="{{ $tickets->url($tickets->lastPage()) }}" class="ui {{ ($tickets->currentPage() == $tickets->lastPage()) ? ' disabled' : '' }} icon item">
	            <i class="angle double right icon"></i>
	        </a>
	    </div>
	</div>

@endsection


@section('js')
	<script src="{{ asset('js/ticket.js') }}"></script>
@endsection