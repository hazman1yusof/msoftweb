@extends('layouts.main')

@section('title', 'Sales Order')

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

#more {display: none;}

@endsection

@section('body')

	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
	<input id="reqdept" name="reqdept" type="hidden" value="{{Session::get('reqdept')}}">
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	@if (Request::get('scope') == 'ALL')
		<input id="recstatus_use" name="recstatus_use" type="hidden" value="ALL">
	@else
		<input id="recstatus_use" name="recstatus_use" type="hidden" value="{{Request::get('scope')}}">
	@endif


	 
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

				<!-- <div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
						  	@if (Request::get('scope') == 'ALL')
						      <option value="All" selected>ALL</option>
						      <option value="OPEN">OPEN</option>
						      <option value="CANCELLED">CANCELLED</option>
						      <option value="REQUEST">REQUEST</option>
						      <option value="SUPPORT">SUPPORT</option>
						      <option value="VERIFIED">VERIFIED</option>
						      <option value="APPROVED">APPROVED</option>
							@elseif (Request::get('scope') == 'SUPPORT')
								<option value="REQUEST">REQUEST</option>
							@elseif (Request::get('scope') == 'VERIFIED')
								<option value="SUPPORT">SUPPORT</option>
							@elseif (Request::get('scope') == 'APPROVED')
								<option value="VERIFIED">VERIFIED</option>
							@endif
					    </select>
	            </div>

	            <div class="col-md-2">
			  		<label class="control-label" for="trandept">Purchase Dept</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div> -->

				<?php 
					$scope_use = 'posted';

					if(Request::get('scope') == 'ALL'){
						$scope_use = 'posted';
					}else if(Request::get('scope') == 'REQUEST'){
						$scope_use = 'posted';
					}else if(Request::get('scope') == 'SUPPORT'){
						$scope_use = 'support';
					}else if(Request::get('scope') == 'VERIFIED'){
						$scope_use = 'verify';
					}else if(Request::get('scope') == 'APPROVED'){
						$scope_use = 'approved';
					}
				?>

				<div id="div_for_but_post" class="col-md-6 col-md-offset-6" style="padding-top: 20px; text-align: end;">
					
					<span id="error_infront" style="color: red"></span>
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
					<button 
						type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="{{$scope_use}}" 
						style="display: none;">
						POST
					</button>

					<button type="button" class="btn btn-default btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
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
			<div class="panel-heading">Sales Order DataEntry Header
				<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
			</div>
			
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
						<div id="jqGridPager"></div>
				</div>
			</div>
		</div>

		<!-- <div type="button" class="click_row pull-right" id="but_print_dtl" style="display: none;background: #337ab7;color: white;min-height: 39px">
			<label class="control-label" style="margin-top: 10px;">Print Label</label>
		</div> -->

	    <div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
	    	<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel">
				<b>SALES AUTO NO: </b><span id="AutoNo_show"></span><br> 
				<b>CUSTOMER NAME: </b><span id="CustName_show"></span>
				
	    		<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Sales Order Data Entry Detail</h5>
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
			<div class="panel-heading">Sales Order Header</div>
				<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
					<form class='form-horizontal' style='width:99%' id='formdata'>
						{{ csrf_field() }}
						<input id="db_idno" name="db_idno" type="hidden">
						<input id="db_source" name="db_source" type="hidden">
						<input id="db_trantype" name="db_trantype" type="hidden">
						<input id="pricebilltype" name="pricebilltype" type="hidden">


						<div class="form-group">
							<label class="col-md-2 control-label" for="db_deptcode">Store Dept</label>	 
							<div class="col-md-4">
								<div class='input-group'>
								<input id="db_deptcode" name="db_deptcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
									<span class="help-block"></span>
								</div>

							<label class="col-md-1 control-label" for="db_invno">Invoice No</label>  
							<div class="col-md-2">
								<input id="db_invno" name="db_invno" type="text" class="form-control input-sm" rdonly>
							</div>

							<label class="col-md-1 control-label" for="db_entrydate">Doc Date</label>  
							<div class="col-md-2">
								<input id="db_entrydate" name="db_entrydate" type="date" maxlength="10" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>" min="<?php $backday= 20; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" 
									max="<?php echo date('Y-m-d');?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_debtorcode">Customer</label>	 
							<div class="col-md-4">
								<div class='input-group'>
								<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<label class="col-md-1 control-label" for="db_hdrtype">Bill Type</label>  
							<div class="col-md-2"> 
								<div class='input-group'>
									<input id="db_hdrtype" name="db_hdrtype" type="text" maxlength="12" class="form-control input-sm text-uppercase" >
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>							
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_mrn">MRN</label>  
							<div class="col-md-4"> 
								<div class='input-group'>
									<input id="db_mrn" name="db_mrn" type="text" maxlength="12" class="form-control input-sm text-uppercase" >
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>	

							<label class="col-md-1 control-label" for="db_termdays">Term</label>  
							<div class="col-md-1">
								<input id="db_termdays" name="db_termdays" type="text" class="form-control input-sm">
							</div>

							<div class="col-md-1">
								<select class="form-control col-md-2" id='db_termmode' name='db_termmode' data-validation="required">
									<option value='DAYS' selected>DAYS</option>
									<option value='MONTH'>MONTH</option>
									<option value='YEAR'>YEAR</option>
								</select> 
							</div>	
						</div>

						<div class="form-group">		
							<label class="col-md-2 control-label" for="db_orderno">Order No</label>  
							<div class="col-md-2"> 
								<input id="db_orderno" name="db_orderno" type="text" class="form-control input-sm text-uppercase" >
							</div>
							
							<label class="col-md-3 control-label" for="db_auditno">Auto No</label>  
							<div class="col-md-2"> 
								<input id="db_auditno" name="db_auditno" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" rdonly>
							</div>

							<label class="col-md-1 control-label" for="posteddate">Posted Date</label>  
								<div class="col-md-2">
								<input id="posteddate" name="posteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
							</div>
						</div>

						<hr>
							
						<div class="form-group">		
							<label class="col-md-2 control-label" for="db_ponum">PO No</label>  
							<div class="col-md-2"> 
								<input id="db_ponum" name="db_ponum" type="text" class="form-control input-sm text-uppercase">
							</div>

							<label class="col-md-3 control-label" for="db_podate">PO Date</label>  
							<div class="col-md-2">
								<input id="db_podate" name="db_podate" type="date" maxlength="10" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_amount">Total Amount</label>
							<div class="col-md-2">
								<input id="db_amount" name="db_amount" type="text" maxlength="11" class="form-control input-sm" value="0.00" rdonly>
							</div>

							<label class="col-md-3 control-label" for="db_recstatus">Record Status</label>  
							<div class="col-md-2">
									<input id="db_recstatus" name="db_recstatus" maxlength="10" class="form-control input-sm" rdonly>
							</div>
						</div>

						<hr>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_approvedby">Approved By</label> 
							<div class="col-md-2">
								<div class='input-group'>
									<input id="db_approvedby" name="db_approvedby" type="text" maxlength="12" class="form-control input-sm text-uppercase">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div> 
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_remark">Remarks</label> 
							<div class="col-md-6"> 
							<textarea class="form-control input-sm text-uppercase" name="db_remark" rows="5" cols="55" maxlength="400" id="db_remark" ></textarea>
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
					<hr/>

					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Sales Order Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti" style="color:red"></div>
					</div>

			</div>
				
			<div id="dialog_remarks" title="Remarks">
			  <div class="panel panel-default">
			    <div class="panel-body">
			    	<textarea id='remarks2' name='remarks2' rows='6' class="form-control input-sm text-uppercase" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div>
		</div>
@endsection

@section('scripts')

	<script src="js/finance/SalesOrder/SalesOrder.js"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection