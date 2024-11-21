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
		width: -webkit-fill-available !important;
	}
@endsection('style')

@section('body')
<div class="container mycontainer">
  <div class="row">
	<div class="col-md-9">
		<div class="panel panel-default" style="height: 230px;">
			<div class="panel-heading">Item Movement Report</div>
			<div class="panel-body">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					<input type="hidden" name="action" >
					<div class="form-group">
						<div class="col-md-6">
						  <label class="control-label" for="datefrom">Date From</label>  
							<input type="date" name="datefrom" id="datefrom" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-01')}}">
			      </div>
						<div class="col-md-6">
						  <label class="control-label" for="dateto">Date To</label>  
							<input type="date" name="dateto" id="dateto" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-t')}}">
			      </div>
			    </div>

				</form>
			</div>
		</div> 
	</div>

	<div class="col-md-3">
		<div class="panel panel-default" style="height: 480px;">
			<div class="panel-body">
				<div class='col-md-12 btnform' style="padding:0px">
				 <fieldset>
					<button name="export_dbacthdr" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_dbacthdr'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Dbacthdr
					</button>

					<button name="export_billdet" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_billdet'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Billdet
					</button>
					
					<button name="export_billsum" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_billsum'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Billsum
					</button>
					
					<button name="export_ivtxnhd" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_ivtxnhd'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Ivtxnhd
					</button>
					
					<button name="export_ivtxndt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_ivtxndt'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Ivtxndt
					</button>
					
					<button name="export_delordhd" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_delordhd'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Delordhd
					</button>
					
					<button name="export_delorddt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_delorddt'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Delorddt
					</button>
					
					<button name="export_apacthdr" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_apacthdr'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Apacthdr
					</button>
					
					<button name="export_apacthdr" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_product'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Product
					</button>
					
					<button name="export_ivdspdt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_ivdspdt'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download Ivdspdt
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
		var from = $('#datefrom').val();
		var to = $('#dateto').val();

		window.open('./export_csv/table?action='+action+'&from='+from+'&to='+to, '_blank');
	});
});
		
</script>

@endsection