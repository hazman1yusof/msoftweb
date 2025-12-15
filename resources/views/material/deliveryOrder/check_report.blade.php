@extends('layouts.main')

@section('title', 'Check DO')

@section('style')
@endsection('style')

@section('body')
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="container mycontainer">
  <div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="width: 90%;margin: auto;margin-top: 10px;">
				<div class="panel-heading">Check DO GLtran</div>
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12 btnform' style="padding:0px">
					 <fieldset>
					  	<label class="control-label" for="deptcode">Department</label>  
				  		<select id='deptcode' name='deptcode' class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" >
				  			@foreach($purdept as $dept)
					  			@if($dept->deptcode == Session::get('deptcode'))
					  			<option selected>{{$dept->deptcode}}</option>
					  			@else
					  			<option >{{$dept->deptcode}}</option>
					  			@endif
				  			@endforeach
				  		</select>
					  	<label class="control-label" for="month">Month</label>  
							<input type="month" id="month" value="{{\Carbon\Carbon::now()->format('Y-m')}}" class="form-control input-sm" >
							<br/>
							<br/>
							<button data-action="check_do_stockconsign" type="button" class="mybtn btn btn-primary">Check dracc (20010042 and 20010044)</button>
							<br/>
							<br/>
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
		let action = $(this).data('action');
		let deptcode = $('#deptcode').val();
		let year = $('#month').val().slice(0, 4);
		let month = parseInt($('#month').val().split('-')[1], 10);
		let url = './table?action='+action+'&deptcode='+deptcode+'&year='+year+'&period='+month;

		window.open(url, '_blank');
	});
});
		
</script>

@endsection