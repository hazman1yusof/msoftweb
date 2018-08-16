@extends('layouts.main')

@section('title', 'AssetType Setup')

@section('body')

	@include('layouts.default_search_and_table')
		
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="prevnext btn-group pull-right">
				</div>
                {{ csrf_field() }}
                <input type="hidden" name="idno">
				<div class="form-group">
            		<label class="col-md-3 control-label" for="assettype">Asset Type</label>  
                      <div class="col-md-2">
                      	<input id="assettype" name="assettype" type="text" maxlength="8" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-3 control-label" for="description">Description</label>  
                      <div class="col-md-7">
                      <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
                                
                <div class="form-group">
				  <label class="col-md-3 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>
				  </div>
				</div> 

					<div class="form-group">
				<label class="col-md-2 control-label" for="adduser">Created By</label>  
					<div class="col-md-3">
					  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
					</div>

					<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
					  	<div class="col-md-3">
							<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
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

	<script src="js/finance/FA/assettype/assettypeScript.js"></script>

	
@endsection