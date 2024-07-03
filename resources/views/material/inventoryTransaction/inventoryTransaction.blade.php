@extends('layouts.main')

@section('title', 'Inventory Transaction')
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

	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
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

							<div  id="tunjukname" style="display:none">
								<div class='input-group'>
									<input id="supplierkatdepan" name="supplierkatdepan" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						
						</div>

		      </div>
				</div>

				<div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Confirmed">CONFIRMED</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

	            <div class="col-md-2">
			  		<label class="control-label" for="trandept">Transaction Dept</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>

				<div id="div_for_but_post" class="col-md-3 col-md-offset-5" style="padding-top: 20px; text-align: end;">

					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>

					<span id="error_infront" style="color: red"></span>

					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>

					<button 
						type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="posted" 
						style="display: none;">
						POST
					</button>

					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
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
    	<div class="panel-heading">Inventory Data Entry Header
			<a class='pull-right pointer text-primary' style="padding-left: 30px" id='pdfgen1' href="" target="_blank">
				<span class='fa fa-print'></span> Print 
			</a>
		</div>
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
        				<table id="jqGrid" class="table table-striped"></table>
        					<div id="jqGridPager"></div>
    				</div>
    		</div>
		</div>
<!-- 
        	<div class='click_row'>
        		<label class="control-label">Dept</label>
        		<span id="txndeptdepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Type</label>
        		<span id="trantypedepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Document No</label>
        		<span id="docnodepan" style="display: block;">&nbsp</span>
        	</div> -->

	     
		 <div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel">
				<b>DOCUMENT NO: </b><span id="trantype_show"></span> - <span id="docno_show"></span><br>
				<b>DEPARTMENT: </b><span id="txndept_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Inventory Transaction Data Entry Detail</h5>
				</div>
			</div>
			<div id="jqGrid3_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
			</div>	
		</div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Inventory Transaction Header
				<a class='pull-right pointer text-primary' style="padding-left: 30px" id='pdfgen2' href="" target="_blank">
					<span class='fa fa-print'></span> Print 
				</a>			
			</div>
				<div class="panel-body" style="position: relative;">
					<form class='form-horizontal' style='width:99%' id='formdata'>
							{{ csrf_field() }}
							<input id="source" name="source" type="hidden">
							<input id="idno" name="idno" type="hidden">
							<input id="crdbfl" name="crdbfl" type="hidden">
							<input id="isstype" name="isstype" type="hidden">
							<input id="referral" name="referral" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="txndept">Transaction Department</label>
									<div class="col-md-2">
									  <div class='input-group'>
										<input id="txndept" name="txndept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  	</div>

								 <label class="col-md-2 control-label" for="docno">Document No</label>  
						  			<div class="col-md-2">
										<input id="docno" name="docno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div> 	
						  		
								<label class="col-md-2 control-label" for="recno">Record No</label>  
						  			<div class="col-md-2">
										<input id="recno" name="recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>	
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="trantype">Transaction Type</label>
									<div class="col-md-2">
									  <div class='input-group'>
										<input id="trantype" name="trantype" type="text" maxlength="12" class="form-control input-sm text-uppercase">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

								<label class="col-md-2 control-label" for="trandate">Transaction Date</label>
								  	<div class="col-md-2">
										<input id="trandate" name="trandate" type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date('Y-m-d');?>" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value">
								  	</div>

								
						  		<label class="col-md-2 control-label" for="srcdocno">Request No</label>  
						  			<div class="col-md-2" id="srcdocno_parent">
									  	<div class='input-group'>
											<input id="srcdocno" name="srcdocno" type="text" class="form-control input-sm text-uppercase" rdonly>
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
						  			</div>
						  </div>

						  <div class="form-group">
						  		<label class="col-md-2 control-label" for="sndrcvtype">Receiver Type</label>  
						  			<div class="col-md-2 selectContainer" id="sndrcvtype_parent">
						  				<select id="sndrcvtype" name="sndrcvtype" class="form-control" data-validation="required" data-validation-error-msg="Please Enter Value">
							  				<option value="">PLEASE SELECT</option>
											<option value="DEPARTMENT">DEPARTMENT</option>
											<option value="SUPPLIER">SUPPLIER</option>
											<option value="OTHER">OTHER</option>
									    </select>
						  			</div>

						  		<label class="col-md-2 control-label" for="sndrcv">Receiver</label>	  
									<div class="col-md-2" id="sndrcv_parent">
										  <div class='input-group'>
											<input id="sndrcv" name="sndrcv" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  </div>
										  <span class="help-block"></span>
									  </div>

									<label class="col-md-2 control-label" for="trantime">Transaction Time</label>
						  			<div class="col-md-2">
										<input id="trantime" name="trantime" type="time" class="form-control input-sm">
						  			</div>
						  </div>

						  <div class="form-group">
								<label class="col-md-2 control-label" for="amount">Amount</label>  
						  			<div class="col-md-2">
										<input id="amount" name="amount" type="text" class="form-control input-sm" value='0.00' rdonly>
						  			</div>

						  		<label class="col-md-2 control-label" for="recstatus">Status</label>  
								  	<div class="col-md-2">
										<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" rdonly>
								  	</div>	
							</div>
								  	
							<div class="form-group">
					    		<label class="col-md-2 control-label" for="remarks">Remarks</label> 
						    		<div class="col-md-6"> 
						    			<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" ></textarea>
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
				<div class="panel-heading">Inventory Transaction Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<input id="gstpercent" name="gstpercent" type="hidden">
							<input id="convfactoruomcodetrdept" name="convfactoruomcodetrdept" type="hidden" value='1'>
							<input id="convfactoruomcoderecv" name="convfactoruomcoderecv" type="hidden" value='1'>

							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti" style="font-size: bold; color: red"><ol></ol>
						</div>
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
	<script src="js/material/inventoryTransaction/inventoryTransaction.js"></script>
	<!-- <script src="js/material/inventoryTransaction/pdfgen.js"></script> -->
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection