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

					<div class="form-group" >
						<div class="col-md-3">
						  <label class="control-label" for="datefrom">Dept Code</label>  
							<select name="deptcode" id="deptcode" class="form-control input-sm">
							  <option value="khealth">khealth</option>
							  <option value="imp">imp</option>
							  <option value="fkwstr">fkwstr</option>
							</select>
						</div>
					</div>

				</form>
			</div>
		</div> 
	</div>

	<div class="col-md-12">
		<div class="panel panel-default" style="height: 265px;">
			<div class="panel-body" style="padding-left: 35px !important;">
				<div class='col-md-12 btnform' style="padding:0px">
				 <fieldset>
					<button name="export_dbacthdr" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_dbacthdr'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Dbacthdr</b>
					</button>

					<button name="export_billdet" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_billdet'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Billdet</b>
					</button>
					
					<button name="export_billsum" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_billsum'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Billsum</b>
					</button>
					
					<button name="export_ivtxnhd" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_ivtxnhd'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Ivtxnhd</b>
					</button>
					
					<button name="export_ivtxndt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_ivtxndt'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Ivtxndt</b>
					</button>
					
					<button name="export_delordhd" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_delordhd'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Delordhd</b>
					</button>
					
					<button name="export_delorddt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_delorddt'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Delorddt</b>
					</button>
					
					<button name="export_apacthdr" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_apacthdr'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Apacthdr</b>
					</button>
					
					<button name="export_apacthdr" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_product'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Product</b>
					</button>
					
					<button name="export_ivdspdt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_ivdspdt'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Ivdspdt</b>
					</button>
					
					<button name="export_ivdspdt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_dballoc'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Dballoc</b>
					</button>
					
					<button name="export_ivdspdt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_apalloc'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Apalloc</b>
					</button>
					
					<button name="export_apactdtl" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_apactdtl'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Apactdtl</b>
					</button>
					
					<button name="export_stockloc" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_stockloc'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Stockloc</b>
					</button>
					
					<button name="export_stockexp" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_stockexp'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>Stockexp</b>
					</button>
					
					<button name="export_stkcnthd" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_stkcnthd'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>StkcntHD</b>
					</button>
					
					<button name="export_stkcntdt" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='export_stkcntdt'>
						<span class="fa fa-file-excel-o fa-lg"></span> Download <b>StkcntDT</b>
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
		var deptcode = $('#deptcode').val();

		window.open('./export_csv/table?action='+action+'&from='+from+'&to='+to+'&deptcode='+deptcode, '_blank');
	});
});
		
</script>

@endsection