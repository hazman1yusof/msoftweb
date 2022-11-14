@extends('layouts.main')

@section('style')

	.form-inline > select{
		margin: 2px 0px;
	}

@endsection

@section('title', 'User Maintenance')

@section('body')
	
	@include('layouts.default_search_and_table')


	<!-------------------------------- table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%;' id='formdata'>
			<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
				<input id="id" name="id" type="hidden">

				<div class="form-group">
				  <label class="col-md-2 control-label" for="username">Username</label>  
				  <div class="col-md-4">
				  	<input id="username" name="username" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>
                </div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="name">Name</label>  
				  <div class="col-md-10">
				  	<input id="name" name="name" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                </div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="password">Password</label>  
				  <div class="col-md-4">
				  <input id="password" name="password" type="text" class="form-control input-sm" data-validation="required">
				  </div>

				  <label class="col-md-2 control-label" for="groupid">Group</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="groupid" name="groupid" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
                </div>

                <div class="form-group">


				  <label class="col-md-2 control-label" for="programmenu">Menu</label>  
				  <div class="col-md-4">
				  <input id="programmenu" name="programmenu" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  </div>

				  <label class="col-md-2 control-label" for="dept">Department</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="dept" name="dept" type="text" class="form-control input-sm text-uppercase">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>

                </div>

                <div class="form-group">
                	<label class="control-label col-md-2">Annual Leave Color</label>
				  	<div class="col-md-1">
		  				<span style="cursor: pointer;display:inline-block;border: 1px solid black;" class="colorpointer" id='pt_ALcolor' data-column='ALcolor'>
							<img src="img/paint.png" style="width:30px" alt="..." id="imgid">
						</span>
		  				<input type='color' id='ALcolor' name='ALcolor' class="form-control input-sm bg_color" value="#ffffff" style="display: none;">
				  	</div>

                	<label class="control-label col-md-2">Discharge Pt. Color</label>
				  	<div class="col-md-1">
		  				<span style="cursor: pointer;display:inline-block;border: 1px solid black;" class="colorpointer" id='pt_DiscPTcolor' data-column='DiscPTcolor'>
							<img src="img/paint.png" style="width:30px" alt="..." id="imgid">
						</span>
		  				<input type='color' id='DiscPTcolor' name='DiscPTcolor' class="form-control input-sm bg_color" value="#ffffff" style="display: none;">
				  	</div>


                	<label class="control-label col-md-2">Cancel Pt. Color</label>
				  	<div class="col-md-1">
		  				<span style="cursor: pointer;display:inline-block;border: 1px solid black;" class="colorpointer" id='pt_CancelPTcolor' data-column='CancelPTcolor'>
							<img src="img/paint.png" style="width:30px" alt="..." id="imgid">
						</span>
		  				<input type='color' id='CancelPTcolor' name='CancelPTcolor' class="form-control input-sm bg_color" value="#ffffff" style="display: none;">
				  	</div>


                	<label class="control-label col-md-2">Current Pt. Color</label>
				  	<div class="col-md-1">
		  				<span style="cursor: pointer;display:inline-block;border: 1px solid black;" class="colorpointer" id='pt_CurrentPTcolor' data-column='CurrentPTcolor'>
							<img src="img/paint.png" style="width:30px" alt="..." id="imgid">
						</span>
		  				<!-- <span style="font-size:2em;cursor: pointer;" class="colorpointer" id='pt_CurrentPTcolor' data-column='CurrentPTcolor'>
							<img src="img/paint.png" style="width:30px;border-bottom:solid;border-bottom-width:5px" alt="..." id="imgid">
						</span> -->
		  				<input type='color' id='CurrentPTcolor' name='CurrentPTcolor' class="form-control input-sm bg_color" value="#ffffff" style="display: none;">
				  	</div>

				</div>

                <div class="form-inline">
                 <label class="control-label col-md-2">Cashier</label>
				  	<select class="form-control col-md-4" id='cashier' name='cashier'>
				  		<option value='1'>Yes</option>
				  		<option value='0' selected>No</option>
				  	</select>

                 <label class="control-label col-md-2">Billing</label>
				  	<select class="form-control col-md-4" id='billing' name='billing'>
				  		<option value='1'>Yes</option>
				  		<option value='0' selected>No</option>
				  	</select>

                 <label class="control-label col-md-2">Nurse</label>
				  	<select class="form-control col-md-4" id='nurse' name='nurse'>
				  		<option value='1'>Yes</option>
				  		<option value='0' selected>No</option>
				  	</select>

				  <div class="clearfix"></div>

                 <label class="control-label col-md-2">Doctor</label>
				  	<select class="form-control col-md-4" id='doctor' name='doctor'>
				  		<option value='1'>Yes</option>
				  		<option value='0' selected>No</option>
				  	</select>

                 <label class="control-label col-md-2">Register</label>
				  	<select class="form-control col-md-4" id='register' name='register'>
				  		<option value='1'>Yes</option>
				  		<option value='0' selected>No</option>
				  	</select>

				  <label class="control-label col-md-2">Price View</label>
				  	<select class="form-control col-md-4" id='priceview' name='priceview'>
				  		<option value='1'>Yes</option>
				  		<option value='0' selected>No</option>
				  	</select>
				</div>
                
			</form>
		</div>

@endsection

@section('scripts')

	<script src="js/setup/user_maintenance/user_maintenance.js"></script>
	
@endsection