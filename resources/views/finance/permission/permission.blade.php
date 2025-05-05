@extends('layouts.main')

@section('title', 'permission')

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


	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
			<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
					  	<div class="col-md-2">
					  		<label class="control-label" for="Scol">Search By : </label>  
					  			<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
		             	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
								<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
							
						</div>
		            </div>
				</div>
	        	</fieldset> 
		</form>
		    
		<div class="panel panel-default">
		    <div class="panel-heading">Permission Header</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>


		

	    <div class="panel panel-default" id="gridpermissiondtl_c" style="position: relative;">
	    <!-- t=input type="button" class="click_row pull-right btn btn-primary" id="but_cando">
			<label class="control-label" style="margin-top: 10px;">Active</label>
        </input> -->
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#gridpermissiondtl_panel">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px"></i>Permission Detail
		    </div>

	    	<input type="button" class="btn btn-sm btn-primary pull-right show_deactive" style="position:absolute;right:60px;top: 8px" id="but_show_deactive" value="Show Deactive">
		
			<div id="gridpermissiondtl_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="gridpermissiondtl" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
			</div>	
		</div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

		
	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-body" style="position: relative;">
			<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">
				<input type="hidden" name="deptcode" id="deptcode">

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="authorid">Author ID</label>  
				  		<div class="col-md-3">
					 		 <div class='input-group'>
								<input id="authorid" name="authorid" type="text" maxlength="15" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					 		 </div>
					  		<span class="help-block"></span>
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
				  			<input id="password" name="password" type="password" maxlength="15" class="form-control input-sm" rdonly>
				  		</div>
				</div>

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked rdonly>Active</label>
							<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' rdonly>Deactive</label>
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
						  	<input id="adddate" name="adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					</div>
						    	
				</div>   
			</form>
			</div>			         
        </div>     

			<div class='panel panel-info'>
				<div class="panel-heading">Permission Detail</div>
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

		 <!--------------------------------permissiondtl Form ------------------>

		<div id="permissiondtl" title="Permission Detail" >
        	<form class='form-horizontal' style='width:99%' id='Fpermissiondtl'>
			
				{{ csrf_field() }}
				<input type="hidden" id="d_idno" name="dtl_idno">
				<input type="hidden" id="d_authorid" name="dtl_authorid">
            
            	<div class="form-group">
				 	<label class="col-md-2 control-label" for="d_authorid">User ID</label>  
				  		<div class="col-md-2">
				  			<input name="dtl_authorid" type="text" maxlength="12" class="form-control input-sm" rdonly>
				  		</div>

				  	<label class="col-md-2 control-label" for="d_deptcode">Department</label>  
				  		<div class="col-md-2">
					  		<div class='input-group'>
								<input id="d_deptcode" name="dtl_deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					 		<span class="help-block"></span>
				  		</div>	
                </div>
            
            	<div class="form-group">
					<label class="col-md-2 control-label" for="d_recstatus">Authorize Status</label>  
						<div class="col-md-2">
							<select id="d_recstatus" name="dtl_recstatus" class="form-control input-sm">
							    <option value="SUPPORT">SUPPORT</option>
							    <option value="VERIFIED">VERIFIED</option>
							    <option value="RECOMMENDED1">RECOMMENDED1</option>
							    <option value="RECOMMENDED2">RECOMMENDED2</option>
							    <option value="APPROVED">APPROVED</option>
							    <option value="DELIVERED">DELIVERED</option>
							</select>
						</div>	

					<label class="col-md-2 control-label" for="d_trantype">Type</label>  
						<div class="col-md-2">
							<select id="d_trantype" name="dtl_trantype" class="form-control input-sm">
							    <option value="PV">Payment Voucher</option>
							    <option value="PD">Payment Deposit</option>
							    <option value="SO">Sales Order</option>
							    <option value="DP">Direct Payment</option>
							</select>
						</div>
                </div>		

                <div class="form-group">
				  <label class="col-md-2 control-label" for="d_cando">Recstatus</label> 
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="dtl_cando" value='ACTIVE' checked readonly>Active</label>
					<label class="radio-inline"><input type="radio" name="dtl_cando" value='DEACTIVE' >Deactive</label>
				  </div>
				</div> 
                
                <div class="form-group">
				  	<label class="col-md-2 control-label" for="d_minlimit">Min Limit</label>  
				  		<div class="col-md-2">
				  			<input id="d_minlimit" name="dtl_minlimit" type="text" class="form-control input-sm" value="0.00">
				  		</div>

				  	<label class="col-md-2 control-label" for="d_maxlimit">Max Limit</label>  
				  		<div class="col-md-2">
				  			<input id="d_maxlimit" name="dtl_maxlimit" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" data-validation="required">
				  		</div>
                </div>
			</form>
		</div>
		<!------------------------------end permissiondtl---------------------------------->	

			
	</div>
	@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex',3);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex',4);
					$("td#input_jqGridPager input.ui-pg-input.form-control").on('focus',onfocus_pageof);
					if($('table#jqGrid').data('enter')){
						$('td#input_jqGridPager input.ui-pg-input.form-control').focus();
						$("table#jqGrid").data('enter',false);
					}

				});
			}

			function onfocus_pageof(){
				$(this).keydown(function(e){
					var code = e.keyCode || e.which;
					if (code == '9'){
						e.preventDefault();
						$('input[name=Stext]').focus();
					}
				});

				$(this).keyup(function(e) {
					var code = e.keyCode || e.which;
					if (code == '13'){
						$("table#jqGrid").data('enter',true);
					}
				});
			}
			
		});
	</script>

	<script src="js/finance/permission/permission.js?v=1.6"></script>

@endsection