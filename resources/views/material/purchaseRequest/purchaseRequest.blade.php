@extends('layouts.main')

@section('title', 'Purchase Request')

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
	<input id="reqdept" name="reqdept" type="hidden" value="{{Session::get('reqdept')}}">
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	@if (Request::get('scope') == 'ALL')
		<input id="recstatus_use" name="recstatus_use" type="hidden" value="POSTED">
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
			  		<label class="control-label" for="trandept">Purchase Dept</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>

				<div id="div_for_but_post" class="col-md-6 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
					<span id="error_infront" style="color: red"></span>
					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">
						@if (Request::get('scope') == 'ALL')
							{{'POST ALL'}}
						@else
							{{Request::get('scope').' ALL'}}
						@endif
					</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_post_single_jq" data-oper="posted" style="display: none;">
						@if (Request::get('scope') == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
					<button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="soft_cancel" style="display: none;">CANCEL</button>
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
		    	<div class="panel-heading">Purchase Request DataEntry Header
					<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
		    	</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

        	<div class='click_row'>
        		<label class="control-label">Request No</label>
        		<span id="reqnodepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Request Dept</label>
        		<span id="reqdeptdepan" style="display: block;">&nbsp</span>
        	</div>
        	<div type="button" class="click_row pull-right" id="but_print_dtl" style="display: none;background: #337ab7;color: white;min-height: 39px">
				<label class="control-label" style="margin-top: 10px;">Print Label</label>
        	</div>

	    <div class="panel panel-default" id="jqGrid3_c">
	    	<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel">
	    		<i class="fa fa-angle-double-up" style="font-size:24px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px"></i>Purchase Request DataEntry Detail
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
			<div class="panel-heading">Purchase Request Header</div>
				<div class="panel-body" style="position: relative;">
					<form class='form-horizontal' style='width:99%' id='formdata'>
							{{ csrf_field() }}
							<input id="referral" name="referral" type="hidden">
							<input id="idno" name="purreqhd_idno" type="hidden">
							<input id="purreqhd_purordno" name="purreqhd_purordno" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_reqdept">Request Department</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="purreqhd_reqdept" name="purreqhd_reqdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

						  		<label class="col-md-2 control-label" for="purreqhd_purreqno">Request No.</label>  
						  			<div class="col-md-2">
										<input id="purreqhd_purreqno" name="purreqhd_purreqno" type="text" maxlength="30" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_prdept">Purchase Department</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="purreqhd_prdept" name="purreqhd_prdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

						  		<label class="col-md-2 control-label" for="purreqhd_recno">Record No.</label>  
						  			<div class="col-md-2">
										<input id="purreqhd_recno" name="purreqhd_recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_suppcode">Supplier Code</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="purreqhd_suppcode" name="purreqhd_suppcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>
							</div>

							<hr/>
							<div class="form-group">
						  		<label class="col-md-2 control-label" for="purreqhd_perdisc">Discount (%)</label>
						  			<div class="col-md-3">
										<input id="purreqhd_perdisc" name="purreqhd_perdisc" type="text" maxlength="11" class="form-control input-sm" value="0.00">
						  			</div>

					  			<label class="col-md-2 control-label" for="purreqhd_amtdisc">Amount Discount</label>
					  			<div class="col-md-3">
									<input id="purreqhd_amtdisc" name="purreqhd_amtdisc" type="text" class="form-control input-sm" value="0.00">
					  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_subamount">Subamount</label>
						  			<div class="col-md-3">
										<input id="purreqhd_subamount" name="purreqhd_subamount" type="text" maxlength="11" class="form-control input-sm" value="0.00">
						  			</div>

						  		<label class="col-md-2 control-label" for="purreqhd_totamount">Total Amount</label>  
						  			<div class="col-md-3">
										<input id="purreqhd_totamount" name="purreqhd_totamount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.0000" value='0.00' rdonly>
						  			</div>
						  			
							</div>

							<div class="form-group">
					  			<label class="col-md-2 control-label" for="purreqhd_purreqdt">Request Date</label>
					  			<div class="col-md-3">
									<input id="purreqhd_purreqdt" name="purreqhd_purreqdt" data-validation="required" type="date" class="form-control input-sm">
					  			</div>

								<label class="col-md-2 control-label" for="purreqhd_recstatus">Status</label>  
								<div class="col-md-3">
										<input id="purreqhd_recstatus" name="purreqhd_recstatus" maxlength="10" class="form-control input-sm" rdonly>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_remarks">Remark</label>   
						  			<div class="col-md-6">
						  				<textarea rows="5" id='purreqhd_remarks' name='purreqhd_remarks' class="form-control input-sm" ></textarea>
						  			</div>
					    	</div>

					    	<div class="form-group data_info">
						    	<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purreqhd_upduser">Last Entered By</label>  
						  			<input id="purreqhd_upduser" name="purreqhd_upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purreqhd_upddate">Last Entered Date</label>
						  			<input id="purreqhd_upddate" name="purreqhd_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purreqhd_authpersonid">Authorized By</label>  
						  			<input id="purreqhd_authpersonid" name="purreqhd_authpersonid" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purreqhd_authdate">Authorized Date</label>
						  			<input id="purreqhd_authdate" name="purreqhd_authdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
							</div>
					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Purchase Request Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<!-- <input id="gstpercent" name="gstpercent" type="hidden">
							<input id="convfactor_uom" name="convfactor_uom" type="hidden" value='1'>
							<input id="convfactor_pouom" name="convfactor_pouom" type="hidden" value='1'> -->
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
			    	<textarea id='remarks2' name='remarks2' rows='6' class="form-control input-sm" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div>
		</div>
@endsection


@section('scripts')

	<script src="js/material/purchaseRequest/purchaseRequest.js"></script>
	<!-- <script src="js/material/purchaseRequest/pdfgen.js"></script> -->
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection