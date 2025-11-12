@extends('layouts.main')

@section('title', 'Item Movement Report')

@section('style')
	body{
		background: #00808024 !important;
	}
	.container.mycontainer{
		padding-top: 5%;
	}
	.mycontainer .panel-default {
	    border-color: #9bb7b7 !important;
	}
	.mycontainer .panel-default > .panel-heading {
	    background-image: linear-gradient(to bottom, #b4cfcf 0%, #c1dddd 100%) !important;
    	font-weight: bold;
	}
	.mycontainer .mybtnpdf{
		background-image: linear-gradient(to bottom, #ffbbbb 0%, #ffd1d1 100%) !important;
    	color: #af2525;
    	border-color: #af2525;
    	margin-bottom: 5px;
	}
	.mycontainer .mybtnxls{
		background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 100%) !important;
	    color: darkgreen;
	    border-color: darkgreen;
    	margin-bottom: 20px;
	}
	.mycontainer .btnvl {
	  	border-left: 1px solid #386e6e;
	    width: 0px;
	    padding: 0px;
	    height: 32px;
	    cursor: unset;
	    margin: 0px 7px;
	}
	legend{
		margin-bottom: 5px !important;
		font-size: 12px !important;
		font-weight:bold;
	}
	.btnform .btn{
		width: 30% !important;
	}
	.btnform .btn b{
		letter-spacing: 0.5px !important;
	}
@endsection('style')

@section('body')
<input id="process_" type="hidden" value="{{ $process_ }}">
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="container mycontainer">
  <div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="height: 260px;width: 90%;margin: auto;">
				<div class="panel-heading">Interface Trial Balance</div>
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12 btnform' style="padding:0px">
					 <fieldset>
						<!-- <button name="gltb_run" type="button" class="mybtn btn btn-sm" data-btntype='gltb_run'>
						 RUN GLTB
						</button> -->
						<button id="gltb" type="button" class="mybtn btn btn-primary" @if($process_ == 'true') {{'disabled'}} @endif style="width:50%" >
						  @if($process_ == 'true') {{'Processing..'}} <i class="fa fa-refresh fa-spin fa-fw">@else {{'Process'}} @endif</i>
						</button>
						<input type="month" id="month" value="{{\Carbon\Carbon::now()->subMonth()->format('Y-m')}}" min="2025-05" class="form-control" style="width:50%;display: inline;">
						<br/><br/>
						<p>Last Run Status : <span id="gltb_status"></span><p/>
						<p>Last Run From : <span id="gltb_datefr"></span><p/>
						<p>Last Run To : <span id="gltb_dateto"></span><p/>
						<p>Last Run Period : <span id="gltb_period"></span></p>
						<p>Last Run Year : <span id="gltb_year"></span></p>
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


	let intervalId = null;

	function startProcessInterval() {
	  intervalId = setInterval(myTask, 5000);
	}
	function stopProcessInterval() {
	  if (intervalId !== null) {
	      clearInterval(intervalId);
	      intervalId = null;
	  }
	}
	if($('#process_').val() == 'false'){
		startProcessInterval();
	}

  function myTask() {
    $.get( './gltb/table?action=check_gltb_process', function( data ) {
			
		},'json').done(function(data) {
	  	var val = $('#month').val();

	    const [year, month] = val.split('-').map(Number);

	    if (month <= 4 && year == '2025') {
	    	$("#gltb").prop('disabled',true);
	    }else{
	    	$("#gltb").prop('disabled',false);
	    	if(data.jobdone=='true'){
	    		stopProcessInterval();
					$('#gltb').attr('disabled',false);
					$('#gltb').html('Process');
				}else{
					$('#gltb').attr('disabled',true);
					$('#gltb').html('Processing.. <i class="fa fa-refresh fa-spin fa-fw">');
				}
				$('#gltb_status').text(data.status);
				$('#gltb_datefr').text(data.datefr);
				$('#gltb_dateto').text(data.dateto);
				$('#gltb_period').text(data.period);
				$('#gltb_year').text(data.year);
	    }
		});
  }

  $('#month').change(function(){
  	const val = $(this).val(); // e.g. "2025-03"
    if (!val) return;

    const [year, month] = val.split('-').map(Number);

    if (month <= 4 && year == '2025') {
    	$("#gltb").prop('disabled',true);
    }else{
    	$("#gltb").prop('disabled',false);
    }
  });

	$("#gltb").click(function() {
	    stopProcessInterval();
			$('#gltb').attr('disabled',true);
			$('#gltb').html('Processing.. <i class="fa fa-refresh fa-spin fa-fw">');
			let dateStr = $('#month').val();

			let [year, month] = dateStr.split("-").map(Number);

      let href = './gltb/form?action=processLink&month='+month+'&year='+year;

      $.post( href,{_token:$('#_token').val()}, function( data ) {

      }).fail(function(data) {

      }).success(function(data){
				startProcessInterval();
      });
	});
});
		
</script>

@endsection