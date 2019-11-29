@extends('layouts.main')

@section('title', 'Region')

@section('style')

input.uppercase {
  text-transform: uppercase;
}

@endsection

@section('body')
		
	@include('layouts.default_search_and_table')
<div id="dialogForm" title="Add Form" >
	<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
		<input type="hidden" name="idno">

		<div class="form-group">
        	<label class="col-md-2 control-label" for="regioncode">Section</label>  
              <div class="col-md-4">
              <input id="regioncode" name="regioncode" type="text" maxlength="30" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
              </div>
		</div>
        
        <div class="form-group">
        	<label class="col-md-2 control-label" for="description">Description</label>  
              <div class="col-md-8">
              <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm uppercase" data-validation="required">
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

				<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
				  	<div class="col-md-2">
						<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				  	</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label" for="adddate">Created Date</label>  
				<div class="col-md-2">
				  	<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
				  	<div class="col-md-2">
						<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				  	</div>
		</div>  

		<div class="form-group">
			<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
				<div class="col-md-2">
				  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
				</div>

				<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
				<div class="col-md-2">
				  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
				</div>

		</div> 

		<div class="form-group">
		<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
				  	<div class="col-md-2">
						<input id="ipaddress" name="ipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				  	</div>
			

				<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
				  	<div class="col-md-2">
						<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				  	</div>
		</div>
	</form>
</div>

@endsection

@section('scripts')
	<script src="js/finance/GL/region/region.js"></script>
@endsection