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
		width: 24% !important;
	}
	.btnform .btn b{
		letter-spacing: 0.5px !important;
	}
@endsection('style')

@section('body')
<div class="container mycontainer">
  <div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="height: 200px;">
				<div class="panel-heading">GLTB</div>
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12 btnform' style="padding:0px">
					 <fieldset>
						<button name="gltb_run_dr" type="button" class="mybtn btn btn-sm" data-btntype='gltb_run_dr'>
						 RUN GLTB DR
						</button>
						<button name="gltb_run_cr" type="button" class="mybtn btn btn-sm" data-btntype='gltb_run_cr'>
						 RUN GLTB CR
						</button>
						<button name="gltb_del" type="button" class="mybtn btn btn-sm" data-btntype='gltb_del'>
						 TRUNCATE GLTB
						</button>
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

	$('button.mybtn').click(function(){
		var action = $(this).data('btntype');

		window.open('./gltb/table?action='+action, '_blank');
	});
});
		
</script>

@endsection