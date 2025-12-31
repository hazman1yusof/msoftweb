@extends('layouts.main')

@section('title', 'Finance New Year')

@section('style')
@endsection('style')

@section('body')
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="container mycontainer">
  <div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="width: 90%;margin: auto;margin-top: 10px;">
				@if(Request::get('scope')=='openyear')
				<div class="panel-heading">Open New Year</div>
				@else
				<div class="panel-heading">Close Last Year</div>
				@endif
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12 btnform' style="padding:0px">
					 <fieldset>

					  	<label class="control-label" for="deptcode">Current Year</label> 
							<input type="text" id="curryear" value="{{$curryear}}" class="form-control input-sm" readonly> 
							<br/>
							@if(Request::get('scope')=='openyear')
					  	<label class="control-label" for="month">Next Year</label>  
							<input type="text" id="year2" value="{{$newyear}}" class="form-control input-sm" readonly> 
							@else
					  	<label class="control-label" for="month">Last Year</label>  
							<input type="text" id="year2" value="{{$lastyear}}" class="form-control input-sm" readonly> 
							@endif

							<br/>

							@if(Request::get('scope')=='openyear')
							<button data-action="process_newyear" type="button" class="mybtn btn btn-primary">Process</button>
							@else
							<button data-action="process_lastyear" type="button" class="mybtn btn btn-primary">Process</button>
							@endif

					  </fieldset>
					</div>
				</div>
			</div> 
		</div>
  </div> 
</div>
		
@endsection

@section('scripts')

<script>
$(document).ready(function () {

	$("button.mybtn").click(function() {

		if(confirm('Are you sure to process?')){

			$('button.mybtn').attr('disabled',true);
			$('button.mybtn').html('Processing.. <i class="fa fa-refresh fa-spin fa-fw">');

			let action = $(this).data('action');
			let url = './finance_yearend/table?action='+action+'&curryear='+$('#curryear').val()+'&year2='+$('#year2').val();

			$.get( url, function( data ) {
				
			}).always(function(data) {
				$('button.mybtn').attr('disabled',false);
				$('button.mybtn').html('Process');
			}).fail(function(data){
				alert(data);
			});

		}

	});
});
		
</script>

@endsection