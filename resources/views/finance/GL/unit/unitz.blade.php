@extends('layouts.main')

@section('title', 'Unit')

@section('body')

	@include('layouts.default_search_and_table')
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
			<input type="hidden" name="idno">

				<div class="form-group">
                	<label class="col-md-2 control-label" for="sectorcode">Unit</label>  
                      <div class="col-md-3">
                      <input id="sectorcode" name="sectorcode" type="text" maxlength="20" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-3">
                      <input id="description" name="description" type="text" maxlength="50" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="regioncode">Section</label>  
		  				<div class="col-md-3">
					  		<div class='input-group'>
								<input id="regioncode" name="regioncode" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					  		<span class="help-block"></span>
		  				</div>
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-3">
					<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  </div>
				</div>  
                
				<div class="form-group">
                 		<label class="col-md-2 control-label" for="adduser">Created By</label>  
				  			<div class="col-md-3">
				  			<input id="adduser" name="adduser" type="text" class="form-control input-sm" rdonly hideOne>
				  			</div>
                	
                 		<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
				  			<div class="col-md-3">
				  			<input id="upduser" name="upduser" type="text" class="form-control input-sm" rdonly hideOne>
				  			</div>
                			
                </div>

                <div class="form-group">
					<label class="col-md-2 control-label" for="adddate">Created Date</label>  
						<div class="col-md-3">
						  	<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
						  	<div class="col-md-3">
								<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" rdonly >
						</div>

						<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
						  	<div class="col-md-3">
								<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" rdonly >
						  	</div>
				</div>     

            </form>
		</div>

@endsection


@section('scripts')

	<script src="js/finance/GL/sector/sector.js"></script>
	
@endsection