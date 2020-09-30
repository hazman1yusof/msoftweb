@extends('layouts.main')

@section('title', 'Category')

@section('body')

	@include('layouts.default_search_and_table')
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">
				<input id="source" name="source" type="hidden" value="{{$_GET['source']}}">
				<input id="cattype" name="cattype" type="hidden" value="{{$_GET['cattype']}}">
				<input id="class" name="class" type="hidden" value="{{$_GET['class']}}">
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="catcode">Category</label>  
				  <div class="col-md-4">
				  <input id="catcode" name="catcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
				                  
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-4">
				  <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required">
				  </div>
				</div>
                
                  
                   <div class="form-group">
				  <label class="col-md-2 control-label" for="stockacct">Stock Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="stockacct" name="stockacct" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                                  
                  <label class="col-md-2 control-label" for="woffacct">Write Off Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="woffacct" name="woffacct" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                  
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="cosacct">COS Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="cosacct" name="cosacct" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                       
                  <label class="col-md-2 control-label" for="expacct">Expenses Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="expacct" name="expacct" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                  
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="adjacct">Adjustment Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="adjacct" name="adjacct" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                       
                  <label class="col-md-2 control-label" for="loanacct">Loan Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="loanacct" name="loanacct" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                  
                  
                <div class="form-group">
                  	<label class="col-md-2 control-label" for="povalidate">PO Validate</label>  
					  <div class="col-md-3">
						<label class="radio-inline"><input type="radio" name="povalidate" value='1' checked>Yes</label>
						<label class="radio-inline"><input type="radio" name="povalidate" value='0'>No</label>
					  </div>

                  	<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
						<div class="col-md-2">
						   	<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>			
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
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						<div class="col-md-3">
						  	<input id="ipaddress" name="ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
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

	<script src="js/material/CategoryINV/category.js"></script>

@endsection