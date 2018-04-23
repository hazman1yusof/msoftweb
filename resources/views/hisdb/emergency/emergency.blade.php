@extends('layouts.main')

@section('style')
	.ui-dialog { z-index: 250 !important ;}

	fieldset.scheduler-border {
	    border: 1px groove #ddd !important;
	    padding: 2px;
	    -webkit-box-shadow:  0px 0px 0px 0px #000;
	    box-shadow:  0px 0px 0px 0px #000;
	}

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0;
        margin:0px;
        border-bottom:none;
    }
    .scheduler-border button{
    	margin:3px;
	}


@endsection

@section('css')
	<link href="plugins/glDatePicker/styles/glDatePicker.default.css" rel="stylesheet" type="text/css">
@endsection
@section('title', 'Emergency Department')

@section('body')

	<div class='row'>
		<input id="Type" name="Type" type="hidden" value="{{Request::get('TYPE')}}">
		<form id="searchForm" class="formclass" style='width:99%;position: relative;'>
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
					<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
				<div style="position: absolute;top: 5px;right: 5px">
					<fieldset class="scheduler-border">
					<legend class="scheduler-border">Legend</legend>
						<button type="button" class="btn btn-sm btn-warning" style="">Current</button>
						<button type="button" class="btn btn-sm btn-danger">Cancel</button>
						<button type="button" class="btn btn-sm">Discharge</button>
					</fieldset>
				</div>
				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:15px;padding-top: 10px" >
				    <button id="regBtn" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New</button>
				</div>
				
			 </fieldset> 
		</form>

		<div class="panel panel-default">
			<div class="panel-body">
				<div class='col-md-4' id="colmd_outer">
				   	<div id="mydate" gldp-id="mydate"></div>
				    <div gldp-el="mydate" id="mydate_glpd" style="position:static;top:30px;left:0px;z-index:0;font-size: 28px;"></div>
				</div>
				<div class='col-md-8' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
				</div>
			</div>
		</div>
		<!--   <input type="text" id="mydate" gldp-id="mydate">
          <div gldp-el="mydate" style="width:400px; height:250px; position:absolute;top:30px;left:0px;z-index:1000;font-size: 28px;"></div>
                </div> -->
	</div>

	<div id="registerform" title="Register Form">
	<form class='form-horizontal' style='width:89%' id='registerformdata'>
		<input id="code" name="code" type="hidden" value="{{Session::get('code')}}">
		{{ csrf_field() }}
		<div class="form-group">
		<label for="title" class="col-md-2 control-label">MRN</label>
	        <div class="col-md-2">
				<div class="input-group">
					<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" rdonly >
					<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
				</div>
				<span class='help-block'></span>
			</div>
             <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
			</div>
		</div>	
        <div class="form-group">
		    <label class="col-md-2 control-label" for="Newic">New I.C</label>
			<div class="col-md-2">
				<input type="text" name="Newic" id="Newic" class="form-control input-sm" data-validation="required" >
			</div>		
		   	<label class="col-md-1 control-label" for="Oldic">Old I.C</label>
			<div class="col-md-2">
				<input type="text" name="Oldic" id="Oldic" class="form-control input-sm" data-validation="required" >
			</div>	
        	<label class="col-md-1 control-label" for="DOB">D.O.B</label>
			<div class="col-md-2">
				<input type="date" name="DOB" id="DOB" class="form-control input-sm" data-validation="required" >
			</div>			
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" for="idnumber">Others No</label>
			<div class="col-md-2">
				<input type="idnumber" name="idnumber" id="idnumber" class="form-control input-sm" data-validation="required" >
			</div>
		</div>	
        <div class="form-group">
          <label class="col-md-2 control-label" for="sex">Sex</label>
					<div class="col-md-2">
							<select id='sex' class="form-control input-sm">
							 <option value="pleasechoose" selected>Please Choose</option>
				      		 <option value="M">Male</option>
					         <option value="F">Female</option>
					         <option value="U">Unisex</option>
						    </select>
					</div>
        </div>

        <div class="form-group">
		<label for="title" class="col-md-2 control-label">Race</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" placeholder="Race" id="race" name="race" maxlength="12" rdonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="description_race" name="description_race">
		</div>
		</div>
		 <div class="form-group">
		<label for="title" class="col-md-2 control-label">Financial Class</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" placeholder="Finanncial Class" id="financeclass" name="financeclass" maxlength="12" rdonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="fName" name="fName">
		</div>
		</div>	
		 <div class="form-group">
		<label for="title" class="col-md-2 control-label">Payer</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" placeholder="Payer" id="payer" name="payer" maxlength="12" rdonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="payername" name="payername">
			<input type="hidden" name="paytype" id="paytype">
		</div>
		</div>	
		 <div class="form-group">
		<label for="title" class="col-md-2 control-label">Bill Type</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" placeholder="BillType" id="billtype" name="billtype" maxlength="12" rdonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="description" name="description_bt">
		</div>
		</div>	
		 <div class="form-group">
		<label for="title" class="col-md-2 control-label">Doctor</label>
	        <div class="col-md-2">
			<div class="input-group">
			<input type="text" class="form-control input-sm" placeholder="Doctor" id="doctor" name="doctor" maxlength="12" rdonly>
			<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
			</div>
			<span class='help-block'></span>
			</div>
              <div class="col-md-4">
				<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="docname" name="docname">
		</div>
		</div>		
	</form>		
	</div>

 
@endsection


@section('scripts')

	<script src="js/hisdb/emergency/emergency.js"></script>
	<!-- <script type="text/javascript" src="plugins/glDatePicker/glDatePicker.min.js"></script> -->
	<script type="text/javascript" src="plugins/glDatePicker/glDatePicker.js"></script>
	<script type="text/javascript" src="plugins/glDatePicker/glDatePicker.min.js"></script>
@endsection