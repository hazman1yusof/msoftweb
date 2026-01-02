@extends('layouts.main')

@section('title', 'Year End Inventory')

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
	}
	.mycontainer .mybtnxls{
		background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 100%) !important;
	    color: darkgreen;
	    border-color: darkgreen;
	}
	.mycontainer .btnvl {
	  	border-left: 1px solid #386e6e;
	    width: 0px;
	    padding: 0px;
	    height: 32px;
	    cursor: unset;
	    margin: 0px 7px;
	}
	.btnform .btn{
		width: -webkit-fill-available !important;
	}
@endsection('style')

@section('body')
<div class="container mycontainer">
  <div class="row">
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Create Stock Location without Opening Balance (NEW YEAR RECORD)</div>
			<div class="panel-body">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="action" name="action" type="hidden" value="yearEnd_form">

					<div class="form-group">
						<div class="col-md-6">
						  	<label class="control-label" for="Scol">Dept From</label> 
							<div class='input-group'> 
								<input id="dept_from" name="dept_from" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
			          	</div>
						<div class="col-md-6">
						  	<label class="control-label" for="Scol">Dept To</label>  
							<div class='input-group'>
								<input id="dept_to" name="dept_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
			          	</div>
			        </div>

					<div class="form-group">
						<div class="col-md-6">
						  	<label class="control-label" for="Scol">Item From</label> 
							<div class='input-group'> 
								<input id="item_from" name="item_from" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
			          	</div>
						<div class="col-md-6">
						  	<label class="control-label" for="Scol">Item To</label>  
							<div class='input-group'>
								<input id="item_to" name="item_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
			          	</div>
			        </div>

					<div class="form-group">
						<div class="col-md-6">
						  	<label class="control-label" for="Scol">New Year</label>  
							<input id="year" name="year" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value">
						 </div>
					</div>
				</form>
			</div>
		</div>
	</div> 

	<div class="col-md-3">
		<div class="panel panel-default" style="height: 278px;">
			<div class="panel-body">
				<div class='col-md-12 btnform' style="padding:0px">
					<button name="generate" id="generate" type="button" class="btn btn-sm btn-primary">
						<span class="fa fa-plus-square fa-lg"></span>
						 Generate
					</button>
					<div class="alert alert-info" role="alert" id="ops_wait" style="display: none;margin-top: 20px;">
						<i class="fa fa-refresh fa-spin" aria-hidden="true"></i><strong> Generating : </strong>Please wait 
					</div>
					<div class="alert alert-success" role="alert" id="ops_success" style="display: none;margin-top: 20px;">
						<strong>Operation Success : </strong> 
						<span id="span_counter"></span> new Stock Location are being generated from year <span id="span_year"></span>
					</div>
				</div>
			</div>
		</div>
	</div> 
  </div>
</div>
		
@endsection


@section('scripts')

	<script src="js/material/YearEnd/yearEndStock.js"></script>

@endsection