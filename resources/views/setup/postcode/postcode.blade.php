@extends('layouts.main')

@section('title', 'Postcode Setup')

@section('body')

	@include('layouts.default_search_and_table')
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

			{{ csrf_field() }}
			<input type="hidden" name="idno">


				<div class="form-group">
				    <label class="col-md-2 control-label" for="postcode">Postcode Code</label>  
                        <div class="col-md-3">
                            <input id="postcode" name="postcode" type="text" maxlength="10" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                        </div>
                    <label class="col-md-2 control-label" for="place_name">Place Name</label>  
                        <div class="col-md-3">
                            <input id="place_name" name="place_name" type="text" maxlength="10" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                        </div>
				</div>
                
				<div class="form-group">
				    <label class="col-md-2 control-label" for="district">District</label>  
                        <div class="col-md-3">
                            <input id="district" name="district" type="text" maxlength="10" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                        </div>
                    <label class="col-md-2 control-label" for="statecode">State</label>  
                        <div class="col-md-3">
                            <input id="statecode" name="statecode" type="text" maxlength="10" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                        </div>
                </div>
                
				<div class="form-group">
				    <label class="col-md-2 control-label" for="countryCode">Country</label>  
                        <div class="col-md-3">
                            <input id="countryCode" name="countryCode" type="text" maxlength="10" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                        </div>
                    <label class="col-md-2 control-label" for="effectivedate">Activate Date</label>  
                        <div class="col-md-3">
                            <input id="effectivedate" name="effectivedate" type="text" maxlength="10" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                        </div>
				</div>
                
                <div class="form-group">
					<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
					<div class="col-md-2">
						<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
						<label class="radio-inline"><input type="radio" name="recstatus" value='D' >Deactive</label>
					</div>
				</div>   

				<div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-2">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="upduser">Last Entered</label>  
						<div class="col-md-2">
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
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" rdonly>
						</div>

						<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
						  	<div class="col-md-3">
								<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" rdonly>
						  	</div>
				</div>     
			</form>
		</div>

@endsection


@section('scripts')

	<script src="js/setup/postcode/postcode.js"></script>
	
@endsection