@extends('layouts.main')

@section('title', 'Stock Balance')

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
		<div class="panel panel-default">
			<div class="panel-heading">Stock Balance Report</div>
			<div class="panel-body">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					<input type="hidden" name="action" >

					<div class="form-group">
						<div class="col-md-6">
						  	<label class="control-label" for="Scol">Dept From</label> 
							<div class='input-group'> 
								<input id="dept_from" name="dept_from" type="text" class="form-control input-sm" autocomplete="off">
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
								<input id="item_from" name="item_from" type="text" class="form-control input-sm" autocomplete="off">
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
						  	<label class="control-label" for="Scol">Year</label> 
					  		<select id='year' name='year' class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" ></select>
			          	</div>
						<div class="col-md-6">
						  	<label class="control-label" for="Scol">Period</label>  
					  		<select id='period' name='period' class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" >
					  			<option>1</option>
					  			<option>2</option>
					  			<option>3</option>
					  			<option>4</option>
					  			<option>5</option>
					  			<option>6</option>
					  			<option>7</option>
					  			<option>8</option>
					  			<option>9</option>
					  			<option>10</option>
					  			<option>11</option>
					  			<option>12</option>
					  		</select>
			          	</div>
			        </div>
				</form>
			</div>
		</div> 
	</div>

	<div class="col-md-3">
		<div class="panel panel-default" style="height: 280px;">
			<div class="panel-body">
				<div class='col-md-12 btnform' style="padding:0px">
				 <fieldset>
				  <legend>Stock Sheet :</legend>
					<button name="stockSheet_pdf" type="button" class="mybtn btn btn-sm mybtnpdf">
						<span class="fa fa-file-pdf-o fa-lg"></span> Stock Sheet PDF
					</button>
					<button name="stockSheet_xls" type="button" class="mybtn btn btn-sm mybtnxls">
						<span class="fa fa-file-excel-o fa-lg"></span> Stock Sheet XLS
					</button>
				  </fieldset>

				 <fieldset>
				  <legend>Stock Balance :</legend>
					<button name="stockBalance_pdf" type="button" class="mybtn btn btn-sm mybtnpdf">
						<span class="fa fa-file-pdf-o fa-lg"></span> Stock Balance PDF
					</button>
					<button name="stockBalance_xls" type="button" class="mybtn btn btn-sm mybtnxls">
						<span class="fa fa-file-excel-o fa-lg"></span> Stock Balance XLS
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

	<script src="js/material/stockBalance/stockBalance.js"></script>

@endsection