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

.whtspc_wrap{
	white-space: pre-wrap !important;
}

div#fail_msg{
  padding-left: 40px;
  padding-bottom: 10px;
  color: darkred;
}

.data_info .col-md-2.minuspad-15{
	width: 12.5% !important;
}

#comment_btn_all .comment_count{
	position: absolute;
  top: -8px;
  right: -13px;
  color: white;
  background: indianred;
  padding: 2px 3px;
  line-height: 10px;
  font-size: 10px;
  border-radius: 20%;
}

table#jqGrid2 a.input-group-addon.btn.btn-primary{
	padding: 2px !important;
}

#more {display: none;}

@endsection

@section('body')

	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
	<input id="reqdept" name="reqdept" type="hidden" value="{{Session::get('reqdept')}}">
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	<input id="pcs_dept" name="pcs_dept" type="hidden" value="PCS">

	@if (Request::get('scope') == 'ALL')
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
							<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2" value="@if(!empty(Request::get('recno'))){{Request::get('recno')}}@endif">

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
					      <option value="CANCELLED">CANCELLED</option>
					      <option value="PREPARED">PREPARED</option>
					      <option value="SUPPORT">SUPPORT</option>
					      <option value="VERIFIED">VERIFIED</option>
					      <option value="RECOMMENDED1">RECOMMENDED 1</option>
					      <option value="RECOMMENDED2">RECOMMENDED 2</option>
					      <option value="APPROVED">APPROVED</option>
					      <option value="COMPLETED">COMPLETED</option>
					      <option value="PARTIAL">PARTIAL</option>
							@elseif (Request::get('scope') == 'SUPPORT')
								<option value="PREPARED">PREPARED</option>
							@elseif (Request::get('scope') == 'VERIFIED')
								<option value="SUPPORT">SUPPORT</option>
							@elseif (Request::get('scope') == 'RECOMMENDED1')
								<option value="VERIFIED">VERIFIED</option>
							@elseif (Request::get('scope') == 'RECOMMENDED2')
								<option value="RECOMMENDED1">RECOMMENDED 1</option>
							@elseif (Request::get('scope') == 'APPROVED')
								<option value="VERIFIED">VERIFIED</option>
								<option value="RECOMMENDED1">RECOMMENDED 1</option>
								<option value="RECOMMENDED2">RECOMMENDED 2</option>
							@elseif (Request::get('scope') == 'REOPEN')
								<option value="CANCELLED">CANCELLED</option>
							@elseif (Request::get('scope') == 'CANCEL')
								<option value="OPEN">OPEN</option>
					      <option value="PREPARED">PREPARED</option>
					      <option value="SUPPORT">SUPPORT</option>
					      <option value="VERIFIED">VERIFIED</option>
					      <option value="RECOMMENDED1">RECOMMENDED 1</option>
					      <option value="RECOMMENDED2">RECOMMENDED 2</option>
							@endif
				    </select>
        </div>

	    	<div class="col-md-2" id="trandeptSearch">
					<label class="control-label" for="trandept">Request Dept</label> 
						<select id='trandept' class="form-control input-sm">
							<option value="All">ALL</option>
							@foreach($reqdept as $dept_)
								@if(Request::get('scope') == 'ALL' && $dept_->deptcode == Session::get('deptcode'))
								<option value="{{$dept_->deptcode}}" selected>{{$dept_->deptcode}}</option>
								@else
								<option value="{{$dept_->deptcode}}">{{$dept_->deptcode}}</option>
								@endif
							@endforeach
						</select>
				</div>

				<?php 
					$scope_use = 'posted';

					if(Request::get('scope') == 'ALL'){
						$scope_use = 'posted';
					}else if(Request::get('scope') == 'PREPARED'){
						$scope_use = 'posted';
					}else if(Request::get('scope') == 'SUPPORT'){
						$scope_use = 'support';
					}else if(Request::get('scope') == 'VERIFIED'){
						$scope_use = 'verify';
					}else if(Request::get('scope') == 'RECOMMENDED1'){
						$scope_use = 'recommended1';
					}else if(Request::get('scope') == 'RECOMMENDED2'){
						$scope_use = 'recommended2';
					}else if(Request::get('scope') == 'APPROVED'){
						$scope_use = 'approved';
					}else if(Request::get('scope') == 'REOPEN'){
						$scope_use = 'reopen';
					}else if(Request::get('scope') == 'CANCEL'){
						$scope_use = 'cancel';
					}
				?>

				<div id="div_for_but_post" class="col-md-6 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					
					<span id="error_infront" style="color: red"></span>
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
					
					<!-- @if (Request::get('scope') == 'ALL')
					<button type="button" class="btn btn-info btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button type="button" class="btn btn-danger btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
					@endif -->

					@if (Request::get('scope') != 'ALL' && Request::get('scope') != 'REOPEN' && Request::get('scope') != 'CANCEL')
					<button type="button" class="btn btn-danger btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">REJECT</button>
					@endif

					@if (Request::get('scope') == 'REOPEN' && !empty(Request::get('recno')))
					<button type="button" class="btn btn-danger btn-sm" id="but_cancel_from_reject_jq" data-oper="cancel_from_reject" style="display: none;">CANCEL</button>
					@endif

					<button 
						type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="{{$scope_use}}" 
						style="display: none;">
						@if (Request::get('scope') == 'ALL')
							{{'PREPARED'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>
					<!-- <button type="button" class="btn btn-primary btn-sm" id="but_post_single_jq" data-oper="{{$scope_use}}" style="display: none;">
						@if (Request::get('scope') == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button> -->

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
					<a class='pull-right pointer text-primary' style="padding-left: 30px" id='pdfgen1' href="" target="_blank">
						<span class='fa fa-print'></span> Print 
					</a>
					<a class='pull-right pointer text-primary' style="padding-left: 30px" id='attcahment_go'>
				    <span class='fa fa-paperclip'></span> Attachment 
					</a>
					<!-- <a class='pull-right pointer text-primary' style="padding-left: 30px;position: relative;" id='comment_btn_all'>
				    <span class='fa fa-commenting-o'></span> Comment <span class="comment_count">2</span>
					</a> -->
		    	</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

        	<div type="button" class="click_row pull-right" id="but_print_dtl" style="display: none;background: #337ab7;color: white;min-height: 39px">
				<label class="control-label" style="margin-top: 10px;">Print Label</label>
        	</div>

	    <div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
	    	<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel">
				<b>PURCHASE REQUEST NO: </b><span id="purreqno_show"></span><br>
				<b>SUPPLIER NAME: </b><span id="suppcode_show"></span>
				
	    		<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Purchase Request Data Entry Detail</h5>
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

			<!-- attachment -->
			<div class="panel panel-default" style="position: relative;" id="gridAttch_c">
				<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#gridAttch_panel" id="panel_gridpv">

					<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
					<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
					<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
						<h5>Attachment</h5>
					</div>
				</div>
				<div id="gridAttch_panel" class="panel-collapse collapse">
					<div class="panel-body" style="height: calc(100vh - 70px); padding: 0px;">
						<div class='col-md-12' style="padding:0 0 15px 0" >
							<iframe id='attach_iframe' src='' style="height: calc(100vh - 100px);width: 100%; border: none;"></iframe>
						</div>
					</div>
				</div>	
			</div> 
        
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Purchase Request Header
				<a class='pull-right pointer text-primary' id='pdfgen2' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
			</div>
				<div class="panel-body" style="position: relative;padding: 5px 0px 0px 0px !important">
					<form class='form-horizontal' id='formdata'>
						{{ csrf_field() }}
						<input id="referral" name="referral" type="hidden">
						<input id="purreqhd_idno" name="purreqhd_idno" type="hidden">
						<input id="purreqhd_purordno" name="purreqhd_purordno" type="hidden">

						<div class="form-group">
							<label class="col-md-2 control-label" for="purreqhd_prtype">Request Type</label>
							<div class="col-md-10">
								<label class="radio-inline"><input type="radio" name="purreqhd_prtype" data-validation="required" data-validation-error-msg="Please Enter Value" value='Stock' checked>Stock</label>
								<label class="radio-inline"><input type="radio" name="purreqhd_prtype" data-validation="required" data-validation-error-msg="Please Enter Value" value='Asset' selected>Asset</label>
								<label class="radio-inline"><input type="radio" name="purreqhd_prtype" data-validation="required" data-validation-error-msg="Please Enter Value" value='Others' selected>Others</label>
								<label class="radio-inline"><input type="radio" name="purreqhd_prtype" data-validation="required" data-validation-error-msg="Please Enter Value" value='AssetMaintenance' selected>Asset Maintenance</label>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label" for="purreqhd_reqdept">Request Department</label>
							<div class="col-md-4">
								<div class='input-group'>
									<input id="purreqhd_reqdept" name="purreqhd_reqdept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
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
									<input id="purreqhd_prdept" name="purreqhd_prdept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
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
									<input id="purreqhd_suppcode" name="purreqhd_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
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
							<label class="col-md-2 control-label" for="purreqhd_purreqdt">Prepared Date</label>
							<div class="col-md-3">
								<input id="purreqhd_purreqdt" name="purreqhd_purreqdt" data-validation="required" data-validation-error-msg="Please Enter Value" type="date" class="form-control input-sm">
							</div>
							
							<label class="col-md-2 control-label" for="purreqhd_recstatus">Status</label>
							<div class="col-md-3">
								<input id="purreqhd_recstatus" name="purreqhd_recstatus" maxlength="10" class="form-control input-sm" rdonly>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label" for="purreqhd_remarks">Remark</label>
							<div class="col-md-3">
								<textarea rows="5" id='purreqhd_remarks' name='purreqhd_remarks' class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
							</div>
							
							<div id="assetno_div" style="display: none;">
								<label class="col-md-2 control-label" for="purreqhd_assetno">Asset No.</label>
								<div class="col-md-3">
									<div class='input-group'>
										<input id="purreqhd_assetno" name="purreqhd_assetno" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>
							</div>
						</div>
						
						<div class="form-group data_info">
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_requestby">Prepared By</label>
								<input id="purreqhd_requestby" name="purreqhd_requestby" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_supportby">Support By</label>
								<input id="purreqhd_supportby" name="purreqhd_supportby" type="text" maxlength="30" class="form-control input-sm" rdonly>
								<i class="fa fa-info-circle my_remark" aria-hidden="true" id='support_remark_i'></i>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_verifiedby">Verified By</label>
								<input id="purreqhd_verifiedby" name="purreqhd_verifiedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
								<i class="fa fa-info-circle my_remark" aria-hidden="true" id='verified_remark_i'></i>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_recommended1by">Recommend 1</label>
								<input id="purreqhd_recommended1by" name="purreqhd_recommended1by" type="text" maxlength="30" class="form-control input-sm" rdonly>
								<i class="fa fa-info-circle my_remark" aria-hidden="true" id='recommended1_remark_i'></i>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_recommended2by">Recommend 2</label>
								<input id="purreqhd_recommended2by" name="purreqhd_recommended2by" type="text" maxlength="30" class="form-control input-sm" rdonly>
								<i class="fa fa-info-circle my_remark" aria-hidden="true" id='recommended2_remark_i'></i>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_approvedby">Approved By</label>
								<input id="purreqhd_approvedby" name="purreqhd_approvedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
								<i class="fa fa-info-circle my_remark" aria-hidden="true" id='approved_remark_i'></i>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_adduser">Add By</label>
								<input id="purreqhd_adduser" name="purreqhd_adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								@if(Request::get('scope') == 'REOPEN')
									<label class="control-label" for="purreqhd_cancelby">Reject User</label>
									<input id="purreqhd_cancelby" name="purreqhd_cancelby" type="text" maxlength="30" class="form-control input-sm" rdonly>
									<i class="fa fa-info-circle my_remark" aria-hidden="true" id='cancelled_remark_i'></i>
								@else
									<label class="control-label" for="purreqhd_upduser">Last User</label>
									<input id="purreqhd_upduser" name="purreqhd_upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
								@endif
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_requestdate">Prepared Date</label>
								<input id="purreqhd_requestdate" name="purreqhd_requestdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_supportdate">Support Date</label>
								<input id="purreqhd_supportdate" name="purreqhd_supportdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_verifieddate">Verified Date</label>
								<input id="purreqhd_verifieddate" name="purreqhd_verifieddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="recommended1date">Recommend1 Date</label>
								<input id="recommended1date" name="recommended1date" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="recommended2date">Recommend2 Date</label>
								<input id="recommended2date" name="recommended2date" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_approveddate">Approved Date</label>
								<input id="purreqhd_approveddate" name="purreqhd_approveddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purreqhd_adddate">Add Date</label>
								<input id="purreqhd_adddate" name="purreqhd_adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
							</div>
							
							<div class="col-md-2 minuspad-15">
								@if(Request::get('scope') == 'REOPEN')
									<label class="control-label" for="purreqhd_canceldate">Reject Date</label>
									<input id="purreqhd_canceldate" name="purreqhd_canceldate" type="text" maxlength="30" class="form-control input-sm" rdonly>
								@else
									<label class="control-label" for="purreqhd_upddate">Update Date</label>
									<input id="purreqhd_upddate" name="purreqhd_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
								@endif
							</div>
						</div>
					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Purchase Request Detail</div>
					<div class="panel-body" style="padding:4px !important">
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

			<div id="dialog_remarks_oper" title="Remarks">
			  <div class="panel panel-default">
			    <div class="panel-body">
			    	<textarea id='remarks_oper' name='remarks_oper' rows='6' class="form-control input-sm text-uppercase" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div>

			<div id="dialog_remarks_status" title="Remarks for - {{Request::get('scope')}}">
			  <div class="panel panel-default">
			    <div class="panel-body">
			    	<textarea id='remarks_status' name='remarks_status' rows='6' class="form-control input-sm text-uppercase" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div>

			<div id="dialog_remarks_view" title="Remarks">
			  <div class="panel panel-default">
			    <div class="panel-body">
			    	<textarea id='remarks_view' name='remarks_view' readonly rows='6' class="form-control input-sm text-uppercase" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div>

			<!-- <div id="dialog_comment_btn_all" title="Comments">
			  <div class="panel panel-default">
			    <div class="panel-body">
			    	<textarea id='remarks_view' name='remarks_view' readonly rows='6' class="form-control input-sm text-uppercase" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div> -->

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
	<script src="js/material/purchaseRequest/purchaseRequest.js?v=1.16"></script>
	<!-- <script src="js/material/purchaseRequest/pdfgen.js"></script> -->
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection