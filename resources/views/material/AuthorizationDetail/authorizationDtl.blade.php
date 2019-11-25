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

	
					<!-- 	<div id="jqGrid" title="Authorization Detail" >
				        	<form class='form-horizontal' style='width:99%' id='jqGrid'>
							
								{{ csrf_field() }}
								<input type="hidden" id="d_idno" name="dtl_idno">
								<input type="hidden" id="d_authorid" name="dtl_authorid">
				            
				            	<div class="form-group">
								 	<label class="col-md-2 control-label" for="d_authorid">User ID</label>  
								  		<div class="col-md-2">
								  			<input name="dtl_authorid" type="text" maxlength="12" class="form-control input-sm" rdonly>
								  		</div>

								  	<label class="col-md-2 control-label" for="d_deptcode">Dept Code</label>  
								  		<div class="col-md-2">
									  		<div class='input-group'>
												<input id="d_deptcode" name="dtl_deptcode" type="text" class="form-control input-sm" data-validation="required">
												<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  		</div>
									 		<span class="help-block"></span>
								  		</div>	
				                </div>
				            
				            	<div class="form-group">
									<label class="col-md-2 control-label" for="d_recstatus">Record Status</label>  
										<div class="col-md-2">
											<select id="d_recstatus" name="dtl_recstatus" class="form-control input-sm">
											    <option value="Request">Request</option>
											    <option value="Support">Support</option>
											    <option value="Verify">Verify</option>
											    <option value="Approve">Approve</option>
											</select>
										</div>	

									<label class="col-md-2 control-label" for="d_trantype">Trantype</label>  
										<div class="col-md-2">
											<select id="d_trantype" name="dtl_trantype" class="form-control input-sm">
											    <option value="PR">Purchase Request</option>
											    <option value="PO">Purchase Order</option>
											</select>
										</div>
				                </div>		

				                <div class="form-group">
								  <label class="col-md-2 control-label" for="d_cando">CanDo</label> 
								  <div class="col-md-2">
									<label class="radio-inline"><input type="radio" name="dtl_cando" value='A' checked>Active</label>
									<label class="radio-inline"><input type="radio" name="dtl_cando" value='D' >Deactive</label>
								  </div>
								</div> 
				                
				                <div class="form-group">
								  	<label class="col-md-2 control-label" for="d_minlimit">Min Limit</label>  
								  		<div class="col-md-2">
								  			<input id="d_minlimit" name="dtl_minlimit" type="text" class="form-control input-sm" value="1.00" rdonly>
								  		</div>

								  	<label class="col-md-2 control-label" for="d_maxlimit">Max Limit</label>  
								  		<div class="col-md-2">
								  			<input id="d_maxlimit" name="dtl_maxlimit" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
								  		</div>
				                </div>
							</form>
						</div>
 -->

		

	@endsection

@section('scripts')

	<script src="js/material/AuthorizationDetail/authorizationDtl.js"></script>

@endsection