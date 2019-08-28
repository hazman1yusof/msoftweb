@extends('layouts.main')

@section('title', 'Authorization')

@section('style')

.panel-heading.collapsed .fa-angle-double-up,
.panel-heading .fa-angle-double-down {
  display: none;
}

.panel-heading.collapsed .fa-angle-double-down,
.panel-heading .fa-angle-double-up {
  display: inline-block;
}

i.fa {
  cursor: pointer;
  float: right;
 <!--  margin-right: 5px; -->
}

.collapsed ~ .panel-body {
  padding: 0;
}

.clearfix {
	overflow: auto;
}

@endsection

@section('body')

	<!-- //@include('layouts.default_search_and_table') -->
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
	        	</fieldset> 
		</form>
		    
		<div class="panel panel-default">
		    <div class="panel-heading">Authorization Header</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>

	    <div class="panel panel-default" id="gridAuthdtl_c">
		<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#gridAuthdtl_panel">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i>
    			<i class="fa fa-angle-double-down" style="font-size:24px"></i>Authorization Detail</div>
				<div id="gridAuthdtl_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<div class='col-md-12' style="padding:0 0 15px 0">
							<table id="gridAuthdtl" class="table table-striped"></table>
							<div id="jqGridPager3"></div>
						</div>'
					</div>
				</div>	
		</div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

		
	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Authorization Header
					<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
					</div>
				<div class="panel-body" style="position: relative;">
					<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="authorid">Author ID</label>  
				  		<div class="col-md-3">
					 		 <div class='input-group'>
								<input id="authorid" name="authorid" type="text" maxlength="15" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					 		 </div>
					  <!--<span class="help-block"></span>-->
				 		</div>
                </div>
                
                <div class="form-group">                  
                  	<label class="col-md-2 control-label" for="name">Name</label>  
				  		<div class="col-md-5">
				  			<input id="name" name="name" type="text" maxlength="100" class="form-control input-sm" rdonly>
				  		</div>
                </div>
                
                <div class="form-group">
				   	<label class="col-md-2 control-label" for="password">Password</label>  
				 		<div class="col-md-3">
				  			<input id="password" name="password" type="text" maxlength="15" class="form-control input-sm" rdonly>
				  		</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="deptcode">Dept Code</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="deptcode" name="deptcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
				</div>

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="recstatus" value='D' >Deactive</label>
				  		</div>
				</div> 

				   

				<div class="form-group data_info">
					<div class="col-md-6 minuspad-13">
						<label class="control-label" for="upduser">Last Entered By</label>  
						 	<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					</div>
					  			
					<div class="col-md-6 minuspad-13">
						<label class="control-label" for="upddate">Last Entered Date</label>
						  	<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					</div>
					    		
					<div class="col-md-6 minuspad-13">
						<label class="control-label" for="adduser">Check By</label>  
						  	<input id="adduser" name="adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					</div>

					<div class="col-md-6 minuspad-13">
						<label class="control-label" for="adddate">Check Date</label>
						  	<input id="adddate" name="delordhd_adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					</div>
						    	
				</div>
			</div>			         
        </div>        
			</form>

			<div class='panel panel-info'>
				<div class="panel-heading">Authorization Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti"><ol></ol>
						</div>
					</div>
			</div>	
		</div>

		 <!--------------------------------Authdtl Form ------------------>

		<div id="Authdtl" title="Authorization Detail" >
        	<form class='form-horizontal' style='width:99%' id='FAuthdtl'>
			
				{{ csrf_field() }}
				<input type="hidden" name="d_idno">
				<input type="hidden" name="d_lineno_">
            
            	<div class="form-group">
				 	<label class="col-md-2 control-label" for="d_authorid">User ID</label>  
				  		<div class="col-md-2">
				  			<input id="d_authorid" name="d_authorid" type="text" maxlength="12" class="form-control input-sm">
				  		</div>

				  	<label class="col-md-2 control-label" for="d_deptcode">Dept Code</label>  
				  		<div class="col-md-2">
					  		<div class='input-group'>
								<input id="d_deptcode" name="d_deptcode" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					 		<span class="help-block"></span>
				  		</div>	
                </div>
            
            	<div class="form-group">
					<label class="col-md-2 control-label" for="d_recstatus">Record Status</label>  
						<div class="col-md-2">
							<select id="d_recstatus" name="d_recstatus" class="form-control input-sm">
							    <option value="Request">Request</option>
							    <option value="Support">Support</option>
							    <option value="Verify">Verify</option>
							    <option value="Approve">Approve</option>
							</select>
						</div>	

					<label class="col-md-2 control-label" for="d_trantype">Trantype</label>  
						<div class="col-md-2">
							<select id="d_trantype" name="d_trantype" class="form-control input-sm">
							    <option value="PR">Purchase Request</option>
							    <option value="PO">Purchase Order</option>
							</select>
						</div>
                </div>		
                 
                <div class="form-group">
					<label class="col-md-2 control-label" for="d_cando">CanDo</label> 
						<div class="col-md-2"> 
							<select id="d_cando" name="d_cando" class="form-control input-sm">
							    <option value="Yes">Yes</option>
							    <option value="No">No</option>
							</select>
						</div>
                </div>	
                
                <div class="form-group">
				  	<label class="col-md-2 control-label" for="d_minlimit">Min Limit</label>  
				  		<div class="col-md-2">
				  			<input id="d_minlimit" name="d_minlimit" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
				  		</div>

				  	<label class="col-md-2 control-label" for="d_maxlimit">Max Limit</label>  
				  		<div class="col-md-2">
				  			<input id="d_maxlimit" name="d_maxlimit" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
				  		</div>
                </div>
			</form>
		</div>
		<!------------------------------end authdtl---------------------------------->	

			
	</div>
	@endsection

@section('scripts')

	<script src="js/material/Authorization/authorization.js"></script>

@endsection