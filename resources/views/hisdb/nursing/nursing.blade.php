@extends('layouts.main')

@section('title', 'Triage Assessment')

@section('style')

.panel-heading.collapsed .fa-angle-double-up,
.panel-heading .fa-angle-double-down {
	display: none;
}

.panel-heading.collapsed .fa-angle-double-down,
.panel-heading .fa-angle-double-up {
	display: inline-block;
}

i.fa {
	cursor: pointer;
	float: right;
	<!--  margin-right: 5px; -->
}

.collapsed ~ .panel-body {
	padding: 0;
}

.clearfix {
	overflow: auto;
}

input.uppercase {
	text-transform: uppercase;
}

@endsection

@section('body')

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<!-- <form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>
				<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase">

							<div  id="show_chggroup_div" style="display:none">
								<div class='input-group'>
									<input id="show_chggroup" seltext='false' name="show_chggroup" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>
		            </div>
				</div>
			</fieldset> 
		</form> -->
        <br>

        <div class="panel panel-default" id="jqGridPatientBio_c">
		    <div class="panel-heading">Patient Biodata</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<!-- <table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div> -->

					<form class='form-horizontal' style='width:99%' id='formdata'>
			
						<div class="form-group">
							<label class="col-md-2 control-label" for="name">Name</label>  
							<div class="col-md-3">
								<input id="name" name="name" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>

							<label class="col-md-2 control-label" for="icnumber">IC Number</label>  
							<div class="col-md-3">
								<input id="icnumber" name="icnumber" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="DOB">DOB</label>  
							<div class="col-md-3">
								<input id="DOB" name="DOB" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>

							<label class="col-md-2 control-label" for="age">Age</label>  
							<div class="col-md-3">
								<input id="age" name="age" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="doctor">Doctor</label>  
							<div class="col-md-3">
								<input id="doctor" name="doctor" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>

							<label class="col-md-2 control-label" for="MRN">MRN</label>  
							<div class="col-md-3">
								<input id="MRN" name="MRN" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="race">Race</label>  
							<div class="col-md-3">
								<input id="race" name="race" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>

							<label class="col-md-2 control-label" for="sex">Sex</label>  
							<div class="col-md-3">
								<input id="sex" name="sex" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="occupation">Occupation</label>  
							<div class="col-md-3">
								<input id="occupation" name="occupation" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>

							<label class="col-md-2 control-label" for="telephone">Telephone</label>  
							<div class="col-md-3">
								<input id="telephone" name="telephone" type="text" class="form-control input-sm uppercase" frozeOnEdit>
							</div>
						</div>

					</form>

        		</div>
		    </div>
		</div>

        <div class="panel panel-default" id="jqGridTriageInfo_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridTriageInfo_panel">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i> Triage Information 
			</div>
			<div id="jqGridTriageInfo_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGridTriageInfo" class="table table-striped"></table>
						<div id="jqGridPagerTriageInfo"></div>
					</div>
				</div>
			</div>	
		</div>

        <div class="panel panel-default" id="jqGridActDaily_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridActDaily_panel">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i> Activities of Daily Living 
			</div>
			<div id="jqGridActDaily_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGridActDaily" class="table table-striped"></table>
						<div id="jqGridPagerActDaily"></div>
					</div>
				</div>
			</div>	
		</div>

        <div class="panel panel-default" id="jqGridTriPhysical_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridTriPhysical_panel">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i> Triage Physical Assessment 
			</div>
			<div id="jqGridTriPhysical_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGridTriPhysical" class="table table-striped"></table>
						<div id="jqGridPagerTriPhysical"></div>
					</div>
				</div>
			</div>	
		</div>

    </div>
	<!-- ***************End Search + table ********************* -->

@endsection


@section('scripts')

	<script src="js/hisdb/nursing/nursing.js"></script>
	
@endsection