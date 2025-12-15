@extends('layouts.main')

@section('title', 'Scheduler ITEM')

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
				<div class="panel-heading">Interface Item Scheduler</div>
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12 btnform' style="padding:0px">
					 <fieldset>
						<!-- <button name="gltb_run" type="button" class="mybtn btn btn-sm" data-btntype='gltb_run'>
						 RUN GLTB
						</button> -->
						<input type="month" id="month" value="{{\Carbon\Carbon::now()->subMonth()->format('Y-m')}}" min="2025-05" class="form-control" style="width:50%;display: inline;">
						<button id="schduler1" type="button" class="mybtn btn btn-primary">schduler 1 (ivtxndt)</button>
						<button id="schduler2" type="button" class="mybtn btn btn-primary">schduler 2 (qtyonhand)</button>
						<button id="schduler3" type="button" class="mybtn btn btn-primary">schduler 3 (stockexp)</button>
						<br/><br/>
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

	$("#button.mybtn").click(function() {
		
	});
});
		
</script>

@endsection