@extends('layouts.main')

@section('style')
@endsection

@section('content')
	
	<div class='ui column'>
		<div class="ui segment">
			<form id="searchForm" class="ui form">
				<div class="ScolClass field" style="padding:0 0 0 15px">
					<div name='Scol' style='font-weight:bold'>Search By : </div>
				</div>
				<div class="StextClass field">
					<input name="Stext" type="search" placeholder="Search here ..." class="">
				</div>
			</form>
		</div>
		
		<div class="ui teal segment">
			<div>
				<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
			</div>
		</div>
	</div>

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
					  <div class="input-group">
					     <input id="password" name="password" type="password" class="form-control input-sm" data-validation="required">
					     <a class="input-group-addon btn btn-default" id="showpwd"><span id="showpwd_" class="fa fa-eye-slash"></span></a>
					   </div>
				  </div>

				  <label class="col-md-2 control-label" for="groupid">Group</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="groupid" name="groupid" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
                </div>

                <div class="form-group">


				  <label class="col-md-2 control-label" for="programmenu">Menu</label>  
				  <div class="col-md-4">
				  <input id="programmenu" name="programmenu" type="text" maxlength="30" class="form-control input-sm">
				  </div>

				  <label class="col-md-2 control-label" for="dept">Department</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="dept" name="dept" type="text" class="form-control input-sm" data-validation="required" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
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

				  <label class="control-label col-md-2">View All Center</label>
				  	<select class="form-control col-md-4" id='viewallcenter' name='viewallcenter'>
				  		<option value='1'>Yes</option>
				  		<option value='0' selected>No</option>
				  	</select>
				</div>
                
			</form>
		</div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/trirand/css/trirand/ui.jqgrid-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script type="text/ecmascript" src="{{ asset('assets/trirand/i18n/grid.locale-en.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('assets/trirand/jquery.jqGrid.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
    <script type="text/ecmascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/ecmascript" src="{{ asset('assets/form-validator/jquery.form-validator.min.js') }}/"></script>
    <script type="text/javascript" src="{{ asset('js/user_maintenance.js') }}"></script>
	
@endsection