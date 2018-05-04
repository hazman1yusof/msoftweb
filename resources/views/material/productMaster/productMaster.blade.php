@extends('layouts.main')

@section('title', 'Product Master')

@section('body')
	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
			 </fieldset> 
		</form>

		<div class='col-md-12' style="padding:0 0 15px 0;">
			<div class="form-group"> 
			  <div class="col-md-4">
			  	<label class="control-label" for="postGroupcode">Group Code</label>  
			  	<select id="postGroupcode" name="postGroupcode" class="form-control input-sm">
			      <option disabled selected>Please Select First</option>
			      <option value="Asset">Asset</option>
			      <option value="Stock">Stock</option>
			      <option value="Others">Others</option>
			    </select>
              </div>
			  <div class="col-md-4">
			  	<label class="control-label" for="postClass">Class</label>  
			  	<br>
			  	<label class="radio-inline"><input type="radio" id="postClassPharmacy" name="postClass" value='Pharmacy' data-validation="required">Pharmacy</label>
			  	<label class="radio-inline"><input type="radio" id="postClassNon-Pharmacy" name="postClass" value='Non-Pharmacy' data-validation="required">Non-Pharmacy</label>
			  	<label class="radio-inline"><input type="radio" id="postClassOther" name="postClass" value='Others' data-validation="required">Others</label>
			  	<label class="radio-inline"><input type="radio" id="postClassAsset" name="postClass" value='Asset' data-validation="required">Asset</label>
              </div>
             </div>
		</div>

	  <div class="panel panel-default">
		<div class="panel-body">
	    	<div class='col-md-12' style="padding:0 0 15px 0">
	            <table id="jqGrid" class="table table-striped"></table>
	            <div id="jqGridPager"></div>
	        </div>
		</div>
	   </div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">

				<div class="form-group">
                	<label class="col-md-2 control-label" for="itemcode">Item Code</label>  
                    	<div class="col-md-4">
                      		<input id="itemcode" name="itemcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      	</div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-8">
                      <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>

				<div class="form-group">
                   <label class="col-md-2 control-label" for="groupcode">Group Code</label>  
				  <div class="col-md-3">
					<input id="groupcode" name="groupcode" type="text" class="form-control input-sm" frozeOnEdit>
				  </div>

				  	<label class="col-md-2 control-label" for="productcat">Product Category</label>  
				  		<div class="col-md-3">
					  		<div class='input-group'>
								<input id="productcat" name="productcat" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					  		<span class="help-block"></span>
				  		</div>
				</div>

				<div class="form-group">
                   <label class="col-md-2 control-label" for="avgcost">Avg Cost</label>  
				  <div class="col-md-3">
					<input id="avgcost" name="avgcost" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
				  </div>
				</div>

                
                <div class="form-group">

                	 <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
						  <div class="col-md-2">
							<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  </div>

                	<label class="col-md-3 control-label" for="Class">Class</label>  
						<div class="col-md-3">
							<input id="Class" name="Class" type="text" class="form-control input-sm" frozeOnEdit>
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
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" hideOne frozeOnEdit>
						</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" hideOne frozeOnEdit>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						<div class="col-md-3">
						  	<input id="ipaddress" name="ipaddress" type="text" class="form-control input-sm" data-validation="required" hideOne frozeOnEdit>
						</div>

					<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" hideOne frozeOnEdit>
						  	</div>
				</div>      
			</form>
		</div>
	@endsection

@section('scripts')

	<script src="js/material/productMaster/productMaster.js"></script>

@endsection