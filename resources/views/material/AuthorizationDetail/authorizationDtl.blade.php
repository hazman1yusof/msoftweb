@extends('layouts.main')

@section('title', 'Authorization Detail')

@section('body')


	<div class='row'>
		<input id="getYear" name="getYear" type="hidden"  value="{{ Carbon\Carbon::now()->year }}">

		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
						  	<label class="control-label" for="Scol">Search By : </label>  
						  		<select id='Scol' class="form-control input-sm"></select>
			            </div>
	                    <div class="col-md-5">
	                        <label class="control-label"></label>  
							<input id="searchText" name="searchText" type="text" class="form-control input-sm" autocomplete="off"/>
				  		</div>
			        </div>
				</div>
			</fieldset> 
		</form>

		<div class="panel panel-default">
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid" class="table table-striped"></table>
    				<div id="jqGridPager"></div>
				</div>
    		</div>
		</div>
		
	<!-- ***************End Search + table ********************* -->
	
	<div id="dialogForm" title="Authorization Detail" >
        	<form class='form-horizontal' style='width:99%' id='formdata'>
			
				{{ csrf_field() }}
				<input type="hidden" id="idno" name="idno">
				<input type="hidden" id="authorid" name="authorid">
            
            	<div class="form-group">
				 	<label class="col-md-2 control-label" for="authorid">User ID</label>  
				  		<div class="col-md-2">
				  			<div class='input-group'>
								<input id="authorid" name="authorid" type="text" maxlength="15" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					 		 </div>
					  		<span class="help-block"></span>
				  		</div>

				  	<label class="col-md-2 control-label" for="deptcode">Dept Code</label>  
				  		<div class="col-md-2">
					  		<div class='input-group'>
								<input id="deptcode" name="deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					 		<span class="help-block"></span>
				  		</div>	
                </div>
            
            	<div class="form-group">
					<label class="col-md-2 control-label" for="recstatus">Status</label>  
						<div class="col-md-2">
							<select id="recstatus" name="recstatus" class="form-control input-sm">
							    <option value="REQUEST">REQUEST</option>
							    <option value="SUPPORT">SUPPORT</option>
							    <option value="VERIFIED">VERIFIED</option>
							    <option value="APPROVED">APPROVED</option>
							</select>
						</div>	

					<label class="col-md-2 control-label" for="trantype">Trantype</label>  
						<div class="col-md-2">
							<select id="trantype" name="trantype" class="form-control input-sm">
							    <option value="PR">Purchase Request</option>
							    <option value="PO">Purchase Order</option>
							</select>
						</div>
                </div>		

                <div class="form-group">
				  <label class="col-md-2 control-label" for="cando">CanDo</label> 
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="cando" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="cando" value='D' >Deactive</label>
				  </div>
				</div> 
                
                <div class="form-group">
				  	<label class="col-md-2 control-label" for="minlimit">Min Limit</label>  
				  		<div class="col-md-2">
				  			<input id="minlimit" name="minlimit" type="text" class="form-control input-sm" value="1.00" rdonly>
				  		</div>

				  	<label class="col-md-2 control-label" for="maxlimit">Max Limit</label>  
				  		<div class="col-md-2">
				  			<input id="maxlimit" name="maxlimit" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" data-validation="required">
				  		</div>
                </div>
			</form>
		</div>
		<!------------------------------end authdtl---------------------------------->	

			
	</div>	

	@endsection

@section('scripts')

	<script src="js/material/AuthorizationDetail/authorizationDtl.js"></script>

@endsection