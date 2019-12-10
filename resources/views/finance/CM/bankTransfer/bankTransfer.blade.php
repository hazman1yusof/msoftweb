@extends('layouts.main')

@section('title', 'Bank Transfer')

@section('body')

<!--***************************** Search + table ******************-->
	 
<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
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
									<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
								
							</div>

			             </div>
					</div>

					<div id="div_for_but_post" class="col-md-6 col-md-offset-2" style="padding-top: 20px; text-align: end;">
						<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
						<button type="button" class="btn btn-default btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					</div>

			</fieldset> 
		</form>
        
    </div>
	<!-- ***************End Search + table ********************* -->
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">

				<input id="trantype" name="trantype" type="hidden">
				<input id="source" name="source" type="hidden">
			
				<div class="form-group">
				  <label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
				  <div class="col-md-3">
					<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="bankname">Name</label>  
				  <div class="col-md-8">
				  <input id="bankname" name="bankname" type="text" class="form-control input-sm text-uppercase" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="address1">Address</label>  
				  <div class="col-md-8">
				  <input id="address1" name="address1" type="text" class="form-control input-sm text-uppercase" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address2" name="address2" type="text" class="form-control input-sm text-uppercase">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address3" name="address3" type="text" class="form-control input-sm text-uppercase">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="statecode">State Code</label>  
				  <div class="col-md-3">
				  <input id="statecode" name="statecode" type="text" class="form-control input-sm">
				  </div>
				  
				  <label class="col-md-2 control-label" for="postcode">Post Code</label>  
				  <div class="col-md-3">
				  <input id="postcode" name="postcode" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="country">Standard Code</label>  
				  <div class="col-md-3">
				  <input id="country" name="country" type="text" class="form-control input-sm text-uppercase">
				  </div>
				  
				  <label class="col-md-2 control-label" for="contact">Contact Person</label>  
				  <div class="col-md-3">
				  <input id="contact" name="contact" type="text" class="form-control input-sm text-uppercase">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="telno">Telephone No.</label>  
				  <div class="col-md-3">
				  <input id="telno" name="telno" type="text" class="form-control input-sm" >
				  </div>
				  
				  <label class="col-md-2 control-label" for="clearday">Clearing Days</label>  
				  <div class="col-md-3">
				  <input id="clearday" name="clearday" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="faxno">Fax No.</label>  
				  <div class="col-md-3">
				  <input id="faxno" name="faxno" type="text" class="form-control input-sm">
				  </div>
				  
				  <label class="col-md-2 control-label" for="effectdate">Effective Date</label>  
				  <div class="col-md-3">
				  <input id="effectdate" name="effectdate" type="date" data-date="" data-date-format="DD MMMM YYYY" class="form-control input-sm"
                   data-validation="date" >
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="bankaccount">Bank Account No.</label>  
				  <div class="col-md-3">
				  <input id="bankaccount" name="bankaccount" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				  
				  <label class="col-md-2 control-label" for="pctype">Petty Cash</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="pctype" value='1'>Yes</label>
					<label class="radio-inline"><input type="radio" name="pctype" value='0' checked>No</label>
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="glccode">Cost Center</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="glccode" name="glccode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
				  
				  <label class="col-md-2 control-label" for="glaccno">GL Account</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="glaccno" name="glaccno" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
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
	<script src="js/finance/CM/bankTransfer/bankTransfer.js"></script>
@endsection