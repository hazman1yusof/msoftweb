@extends('layouts.main')

@section('title', 'Inventory Request')

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

.whtspc_wrap{
	white-space: pre-wrap !important;
}

div.noti > li{
	color:red;
}

div#fail_msg{
  padding-left: 40px;
  padding-bottom: 10px;
  color: darkred;
}

#more {display: none;}

@endsection

@section('body')

	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
	<input id="reqdept_" name="reqdept_" type="hidden" value="{{Session::get('reqdept')}}">
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	@if (Request::get('scope') == 'all')
		<input id="recstatus_use" name="recstatus_use" type="hidden" value="ALL">
	@else
		<input id="recstatus_use" name="recstatus_use" type="hidden" value="{{Request::get('scope')}}">
	@endif


	 
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

							<div  id="tunjukname" style="display:none">
								<div class='input-group'>
									<input id="supplierkatdepan" name="supplierkatdepan" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
							
						</div>


					  	<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					  		<p id="p_error"></p>
					  	</div>

		             </div>
				</div>

				<div class="col-md-2">
			  	<label class="control-label" for="Status">Status</label>  
				  	<select id="Status" name="Status" class="form-control input-sm">
					  	@if (Request::get('scope') == 'ALL')
				      <option value="All" selected>ALL</option>
				      <option value="OPEN">OPEN</option>
				      <option value="PARTIAL">PARTIAL</option>
				      <option value="COMPLETED">COMPLETED</option>
				      <option value="POSTED">POSTED</option>
				      <option value="CANCELLED">CANCELLED</option>
							@elseif (Request::get('scope') == 'REOPEN')
								<option value="CANCELLED">CANCELLED</option>
							@elseif (Request::get('scope') == 'CANCEL')
								<option value="OPEN">OPEN</option>
							@endif
				    </select>
	      </div>

	      <div class="col-md-2">
			  		<label class="control-label" for="trandept">Request Dept</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>

				<?php 
					$scope_use = 'posted';

					if(Request::get('scope') == 'ALL'){
						$scope_use = 'posted';
					}else if(Request::get('scope') == 'REOPEN'){
						$scope_use = 'reopen';
					}else if(Request::get('scope') == 'CANCEL'){
						$scope_use = 'cancel';
					}
				?>

				<div id="div_for_but_post" class="col-md-6 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
					<span id="error_infront" style="color: red"></span>

					<button 
						type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="{{$scope_use}}" 
						style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>
				</div>
				
			 </fieldset> 
		</form>

		<div class="panel panel-default" id="sel_tbl_panel" style="display:none">
    		<div class="panel-heading heading_panel_">List Of Selected Item</div>
    		<div class="panel-body">
    			<div id="sel_tbl_div" class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid_selection" class="table table-striped"></table>
    				<div id="jqGrid_selectionPager"></div>
				</div>
    		</div>
		</div>

        <div class="panel panel-default">
		    	<div class="panel-heading">Inventory Request DataEntry Header
					<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
		    	</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

        	<!-- <div class='click_row'>
        		<label class="control-label">Request No</label>
        		<span id="reqnodepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Request Dept</label>
        		<span id="reqdeptdepan" style="display: block;">&nbsp</span>
        	</div> -->
        	<div type="button" class="click_row pull-right" id="but_print_dtl" style="display: none;background: #337ab7;color: white;min-height: 39px">
				<label class="control-label" style="margin-top: 10px;">Print Label</label>
        	</div>

	    <div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
	    	<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel">
				<!-- <b>PR NO: </b><span id="purreqno_show"></span><br>
				<b>SUPPLIER NAME: </b><span id="suppcode_show"></span> -->
				
	    		<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Inventory Request Data Entry Detail</h5>
				</div>
			</div>

    		<div id="jqGrid3_panel" class="panel-collapse collapse">
	    		<div class="panel-body">
	    			<div id="" class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid3" class="table table-striped"></table>
            			<div id="jqGridPager3"></div>
    				</div>'
	    		</div>
	    	</div>	
		</div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
				<div class="panel-heading">Inventory Request Header</div>
				<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
					<form class='form-horizontal' style='width:99%' id='formdata'>
							{{ csrf_field() }}
							<input id="referral" name="referral" type="hidden">
							<input id="idno" name="idno" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="reqdept">Request Department</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="reqdept" name="reqdept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

					  		<label class="col-md-2 control-label" for="ivreqno">Request No.</label>  
				  			<div class="col-md-2">
									<input id="ivreqno" name="ivreqno" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="reqtodept">Req. Made To</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="reqtodept" name="reqtodept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

					  		<label class="col-md-2 control-label" for="recno">Record No.</label>  
				  			<div class="col-md-2">
									<input id="recno" name="recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
				  			</div>
							</div>

							<hr/>

							<div class="form-group">		
						  		<label class="col-md-2 control-label" for="reqtype">Request Type</label>  
					  			<div class="col-md-2">
										<input id="reqtype" name="reqtype" type="text" maxlength="11" class="form-control input-sm" value= 'TRANSFER' rdonly>
					  			</div>

									<label class="col-md-2 control-label" for="reqdt">Request Date</label>  
					  			<div class="col-md-2">
										<input id="reqdt" name="reqdt" type="date" maxlength="10" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value"  value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
					  			</div>

						  		<!-- <label class="col-md-2 control-label" for="amount">Amount</label>  
					  			<div class="col-md-2">
										<input id="amount" name="amount" type="text" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.0000" rdonly>
					  			</div> -->

								<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
									<div class="col-md-2">
										<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" rdonly>
								</div>

							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="remarks">Remarks</label>   
						  			<div class="col-md-6">
						  				<textarea rows="5" id='remarks' name='remarks' class="form-control input-sm text-uppercase" ></textarea>
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
			<div class="panel-heading">Purchase Request Detail</div>
				<div class="panel-body">
					<div id="fail_msg"></div>
					<form id='formdata2' class='form-vertical' style='width:99%'>
						<!-- <input id="gstpercent" name="gstpercent" type="hidden">
						<input id="convfactor_uom" name="convfactor_uom" type="hidden" value='1'>
						<input id="convfactor_pouom" name="convfactor_pouom" type="hidden" value='1'> -->
						<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

						<div id="jqGrid2_c" class='col-md-12' style="overflow-y: hidden;overflow-x: hidden;height: calc(100vh - 80px);">
							<table id="jqGrid2" class="table table-striped"></table>
				      <div id="jqGridPager2"></div>
						</div>
					</form>
				</div>

				<div class="noti"><ol></ol>
				</div>
		</div>
				
		
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
	<script src="js/material/inventoryRequest/inventoryRequest.js"></script>
	<!-- <script src="js/material/inventoryRequest/pdfgen.js"></script> -->
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection