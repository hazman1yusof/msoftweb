@extends('layouts.main')

@section('title', 'Check Balance Sheet')

@section('style')
@endsection('style')

@section('body')
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="container mycontainer">
  <div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="width: 90%;margin: auto;margin-top: 10px;">
				<div class="panel-heading">Interface Check Balance Sheet</div>
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12' style="padding:0px">
				  	<label class="control-label" for="month">Year and Month</label>  
						<input type="month" id="month" value="{{\Carbon\Carbon::now()->format('Y-m')}}" min="2025-05" class="form-control input-sm" >
							<br/>
							<br/>
							<button data-action="checkBS" type="button" class="mybtn btn btn-primary">Check</button>
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
		let year = $('#month').val().slice(0, 4);
		let month = parseInt($('#month').val().split("-")[1], 10);
		let url = './table?action='+action+'&year='+year+'&month='+month;

		window.open(url, '_blank');
	});
});
		
</script>

@endsection