@extends('layouts.main')

@section('title', 'Company Setup')

@section('body')

	@include('layouts.default_search_and_table')
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
				{{ csrf_field() }}
				<!-- <input type="hidden" name="idno"> -->

				<div class="form-group">
				  <label class="col-md-2 control-label" for="compcode">Company Code</label>  
				  <div class="col-md-4">
				  <input id="compcode" name="compcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
				</div>
				                  
				  <div class="form-group">
				  <label class="col-md-2 control-label" for="name">Name</label>  
				  <div class="col-md-8">
				  <input id="name" name="name" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="address1">Address</label>  
				  <div class="col-md-8">
				  <input id="address1" name="address1" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
				

				<!--------------------------------
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address2" name="address2" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address3" name="address3" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address4" name="address4" type="text" class="form-control input-sm">
				  </div>
				</div>

				 ------------------>
                				
 				<div class="form-group">
				  <label class="col-md-2 control-label" for="bmppath1">Bmppath</label>  
				  <div class="col-md-4">
				  <input id="bmppath1" name="bmppath1" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				 </div>
				
				<!--------------------------------
				<div class="form-group">
				<div class="col-md-offset-2 col-md-4">
				  <input id="bmppath2" name="bmppath2" type="text" class="form-control input-sm">
				  </div>
				</div>
						 ------------------>

				 <div class="form-group">
				 <label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
						<div class="col-md-2">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" rdonly >
						</div>
				  <label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
				  <div class="col-md-4">
				  <input ids="lastipaddress" name="lastipaddress" type="text" class="form-control input-sm" data-validation="required" rdonly>
				  </div>
				  
				  
				</div>
				  
				 <div class="form-group">
				 	<label class="col-md-2 control-label" for="logo1">Logo</label>  
				  <div class="col-md-4">
				  <input id="logo1" name="logo1" type="text" class="form-control input-sm">
				  </div>


				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-2">
				    <label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D' >Deactive</label>
                </div>
				</div>




			</form>
		</div>

@endsection


@section('scripts')

	<script src="js/setup/compcode/compcode.js"></script>
	
@endsection