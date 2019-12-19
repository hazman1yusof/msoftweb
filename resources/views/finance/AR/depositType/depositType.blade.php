@extends('layouts.main')

@section('title', 'Deposit Type')

@section('body')
	 

	@include('layouts.default_search_and_table')
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">

				<input id="trantype" name="trantype" type="hidden">
				<input id="source" name="source" type="hidden">
			
				<div class="form-group">
				  <label class="col-md-2 control-label" for="hdrtype">Header Type</label>  
				  <div class="col-md-3">
						<input id="hdrtype" name="hdrtype" type="text" maxlength="2" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-8">
				  <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required">
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="depccode">Deposit Cost</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="depccode" name="depccode" type="text" maxlength="10" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
				  
				  <label class="col-md-2 control-label" for="depglacc">Deposit GL Account</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="depglacc" name="depglacc" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="updpayername">Update Payer Name</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input id="updpayername" type="radio" name="updpayername" value='1' data-validation="required" checked>Yes</label>
					<label class="radio-inline"><input type="radio" name="updpayername" value='0' data-validation="">No</label>
				  </div>
				  
				  <label class="col-md-2 control-label" for="updepisode">Auto Allocation</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="updepisode" value='1' data-validation="required" checked>Yes</label>
					<label class="radio-inline"><input type="radio" name="updepisode" value='0' data-validation="">No</label>
				  </div>

				  <label class="col-md-2 control-label" for="manualalloc">Manual Allocation</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="manualalloc" value='1' data-validation="required" checked>Yes</label>
					<label class="radio-inline"><input type="radio" name="manualalloc" value='0' data-validation="">No</label>
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D' >Deactive</label>
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
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

						<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

				</div> 

				<div class="form-group">
				<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						  	<div class="col-md-3">
								<input id="ipaddress" name="ipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
					

						<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						  	<div class="col-md-3">
								<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>
			</form>
		</div>

	@endsection

@section('scripts')
	<script src="js/finance/AR/depositType/depositType.js"></script>
@endsection