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
						  <label class="control-label" for="Scol">Department</label> 
							<div class='input-group'> 
								<input id="dept_from" name="dept_from" type="text" class="form-control input-sm" autocomplete="off" value="">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
			      </div>

						<!-- <div class="col-md-6">
						  <label class="control-label" for="Scol">Dept To</label>  
							<div class='input-group'>
								<input id="dept_to" name="dept_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
			      </div> -->
			    </div>

					<div class="form-group">
						<div class="col-md-6">
						  <label class="control-label" for="Scol">Date From</label>  
							<input type="date" name="datefrom" id="datefrom" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-01')}}">
			      </div>
						<div class="col-md-6">
						  <label class="control-label" for="Scol">Date To</label>  
							<input type="date" name="dateto" id="dateto" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-t')}}">
			      </div>
			    </div>

				</form>
			</div>
		</div> 
	</div>

	<div class="col-md-3">
		<div class="panel panel-default" style="height: 230px;">
			<div class="panel-body">
				<div class='col-md-12 btnform' style="padding:0px">
				 <fieldset>
				  <legend>Slow Item Movement :</legend>
					<!-- <button name="itemMovSlow_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" data-btntype='pdf'>
						<span class="fa fa-file-pdf-o fa-lg"></span> Slow Item Movement PDF
					</button> -->
					<button name="itemMovSlow_excel" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='xls'>
						<span class="fa fa-file-excel-o fa-lg"></span> Slow Item Movement XLS
					</button>
				  </fieldset>

				 <fieldset>
				  <legend>Fast Item Movement :</legend>
					<!-- <button name="itemMovFast_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" data-btntype='pdf'>
						<span class="fa fa-file-pdf-o fa-lg"></span> Fast Item Movement PDF
					</button> -->
					<button name="itemMovFast_excel" type="button" class="mybtn btn btn-sm mybtnxls" data-btntype='xls'>
						<span class="fa fa-file-excel-o fa-lg"></span> Fast Item Movement XLS
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

	<script src="js/material/ItemMovReport/ItemMovReport.js"></script>

@endsection