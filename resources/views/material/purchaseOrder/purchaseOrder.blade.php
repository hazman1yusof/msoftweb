@extends('layouts.main')

@section('title', 'Purchase Order')

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

div.noti > li{
	color:red;
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

div.data_info div.col-md-2{
	width: 20%;
}

table#jqGrid2 a.input-group-addon.btn.btn-primary{
	padding: 2px !important;
}

.frozen-div{overflow:hidden;}

#more {display: none;}

@endsection

@section('body')

	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
	<input id="deldept" name="deldept" type="hidden" value="{{Session::get('deldept')}}">
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	<input id="viewonly" name="viewonly" type="hidden" value="{{Request::get('viewonly')}}">
	<input id="pcs_dept" name="pcs_dept" type="hidden" value="PCS">

	@if (Request::get('scope') == 'ALL')
		<input id="recstatus_use" name="recstatus_use" type="hidden" value="ALL">
	@else
		<input id="recstatus_use" name="recstatus_use" type="hidden" value="{{Request::get('scope')}}">
	@endif
	
	<!--***************************** Search + table *****************************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width: 99%; position: relative;' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<input id="getYear" name="getYear" type="hidden" value="<?php echo date("Y") ?>">
				
				<div class='col-md-12' style="padding: 0 0 5px 0;">
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>
							<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
						</div>
						
						<div class="col-md-5">
							<label class="control-label"></label>
							<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2" value="@if(!empty(Request::get('recno'))){{Request::get('recno')}}@endif">
							
							<div id="tunjukname" style="display: none;">
								<div class='input-group'>
									<input id="supplierkatdepan" name="supplierkatdepan" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<div id="actdate_text" class="form-inline" style="display:none">
								FROM DATE <input id="actdate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
								TO <input id="actdate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
								<button type="button" class="btn btn-primary btn-sm" id="actdate_search">SEARCH</button>
							</div>

						</div>
						
						<div class="col-md-5" style="padding-top: 20px; text-align: center; color: red;">
							<p id="p_error"></p>
						</div>
					</div>
				</div>
				
				<div class="col-md-2">
					<label class="control-label" for="Status">Status :</label>
					<select id="Status" name="Status" class="form-control input-sm">
						@if (Request::get('scope') == 'ALL')
							<option value="All" selected>ALL</option>
							<option value="OPEN">OPEN</option>
							<option value="CANCELLED">CANCELLED</option>
							<option value="PREPARED">PREPARED</option>
							<!-- <option value="SUPPORT">SUPPORT</option> -->
							<option value="VERIFIED">VERIFIED</option>
							<option value="APPROVED">APPROVED</option>
							<option value="COMPLETED">COMPLETED</option>
							<option value="PARTIAL">PARTIAL</option>
							<option value="CLOSE">CLOSE</option>
						@elseif (Request::get('scope') == 'SUPPORT')
							<option value="PREPARED">PREPARED</option>
						@elseif (Request::get('scope') == 'VERIFIED')
							<option value="PREPARED">PREPARED</option>
						@elseif (Request::get('scope') == 'APPROVED')
							<option value="VERIFIED">VERIFIED</option>
						@elseif (Request::get('scope') == 'REOPEN')
							<option value="CANCELLED">CANCELLED</option>
						@elseif (Request::get('scope') == 'CANCEL')
							<option value="OPEN">OPEN</option>
							<option value="PREPARED">PREPARED</option>
							<option value="VERIFIED">VERIFIED</option>
						@elseif (Request::get('scope') == 'FORCE_CLOSE')
							<option value="APPROVED" selected>APPROVED</option>
							<option value="CLOSE">CLOSE</option>
						@endif
					</select>
				</div>
				
				<div class="col-md-2" id="trandeptSearch">
					<label class="control-label" for="trandept">Purchase Dept</label>
					<select id='trandept' class="form-control input-sm">
						<option value="All">ALL</option>
						@foreach($purdept as $dept_)
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
					}else if(Request::get('scope') == 'APPROVED'){
						$scope_use = 'approved';
					}else if(Request::get('scope') == 'REOPEN'){
						$scope_use = 'reopen';
					}else if(Request::get('scope') == 'CANCEL'){
						$scope_use = 'cancel';
					}else if(Request::get('scope') == 'FORCE_CLOSE'){
						$scope_use = 'force_close';
					}
				?>


				<div id="div_for_but_post" class="col-md-6 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>

					@if (Request::get('scope') != 'ALL' && Request::get('scope') != 'REOPEN' && Request::get('scope') != 'CANCEL' && Request::get('scope') != 'FORCE_CLOSE')
					<button type="button" class="btn btn-danger btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">REJECT</button>
					@endif

					@if (Request::get('scope') == 'REOPEN' && !empty(Request::get('recno')))
					<button type="button" class="btn btn-danger btn-sm" id="but_cancel_from_reject_jq" data-oper="cancel_from_reject" style="display: none;">REJECT</button>
					@endif

					<span id="error_infront" style="color: red"></span>
					<!-- <button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button> -->
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
					<!-- <button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
					<button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button> -->
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
	    	<div class="panel-heading">Purchase Order DataEntry Header
	    		<a class='pull-right pointer text-primary' style="padding-left: 30px" id='pdfgen1' href="" target="_blank">
	    			<span class='fa fa-print'></span> Print 
	    		</a>
				<a class='pull-right pointer text-primary' style="padding-left: 30px" id='attcahment_go'>
					<span class='fa fa-paperclip'></span> Attachment 
				</a>
	    	</div>
			
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid" class="table table-striped"></table>
    				<div id="jqGridPager"></div>
  				</div>
    		</div>
		</div>

		<div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel">
				<b>PURCHASE ORDER NO: </b><span class="prdept_show"></span> - <span class="purordno_show"></span><br>
				<b>SUPPLIER NAME: </b><span class="suppcode_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Purchase Order Data Entry Detail</h5>
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

		<div class="panel panel-default" style="position: relative;" id="gridDoHd_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#gridDoHd_panel" id="panel_gridDoHd">
				<b>PURCHASE ORDER NO: </b><span class="prdept_show"></span> - <span class="purordno_show"></span><br>
				<b>SUPPLIER NAME: </b><span class="suppcode_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>GRN Header</h5>
				</div>
			</div>
			<div id="gridDoHd_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="gridDoHd" class="table table-striped"></table>
						<div id="jqGridPagerDoHd"></div>
					</div>
				</div>
			</div>	
		</div>

		<div class="panel panel-default" style="position: relative;" id="gridDoDt_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#gridDoDt_panel" id="panel_gridDoDt">
				<b>PURCHASE ORDER NO: </b><span class="prdept_show"></span> - <span class="purordno_show"></span><br>
				<b>SUPPLIER NAME: </b><span class="suppcode_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>GRN Detail</h5>
				</div>
			</div>
			<div id="gridDoDt_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="gridDoDt" class="table table-striped"></table>
						<div id="jqGridPagerDoDt"></div>
					</div>
				</div>
			</div>	
		</div> 

		<div class="panel panel-default" style="position: relative;" id="gridGRTHd_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#gridGRTHd_panel" id="panel_gridGRTHd">
				<b>PURCHASE ORDER NO: </b><span class="prdept_show"></span> - <span class="purordno_show"></span><br>
				<b>SUPPLIER NAME: </b><span class="suppcode_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>GRT Header</h5>
				</div>
			</div>
			<div id="gridGRTHd_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="gridGRTHd" class="table table-striped"></table>
						<div id="jqGridPagerGRTHd"></div>
					</div>
				</div>
			</div>	
		</div>

		<div class="panel panel-default" style="position: relative;" id="gridGRTDt_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#gridGRTDt_panel" id="panel_gridGRTDt">
				<b>PURCHASE ORDER NO: </b><span class="prdept_show"></span> - <span class="purordno_show"></span><br>
				<b>SUPPLIER NAME: </b><span class="suppcode_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>GRT Detail</h5>
				</div>
			</div>
			<div id="gridGRTDt_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="gridGRTDt" class="table table-striped"></table>
						<div id="jqGridPagerGRTDt"></div>
					</div>
				</div>
			</div>	
		</div>

		<!-- attachment -->
		<div class="panel panel-default" style="position: relative;" id="gridAttch_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#gridAttch_panel" id="panel_gridpv">
				<b>PURCHASE ORDER NO: </b><span class="prdept_show"></span> - <span class="purordno_show"></span><br>
				<b>SUPPLIER NAME: </b><span class="suppcode_show"></span>

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
	<!-------------------------------- End Search + table ------------------>
	
	<div id="dialogForm" title="Add Form">
		<div class='panel panel-info'>
			<div class="panel-heading">Purchase Order Header
				<a class='pull-right pointer text-primary' style="padding-left: 30px" id='pdfgen2' href="" target="_blank">
					<span class='fa fa-print'></span> Print 
				</a>
			</div>
			<div class="panel-body" style="position: relative;padding: 5px 5px 0px 0px !important">
				<form class='form-horizontal' id='formdata'>
					{{ csrf_field() }}
					<input id="purordhd_idno" name="purordhd_idno" type="hidden">
					<input id="referral" name="referral" type="hidden">
					<input id="purordhd_delordno" name="purordhd_delordno" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="purordhd_prtype">Request Type</label>
						<div class="col-md-10">
							<label class="radio-inline"><input type="radio" name="purordhd_prtype" data-validation="required" data-validation-error-msg="Please Enter Value" value='Stock' checked>Stock</label>
							<label class="radio-inline"><input type="radio" name="purordhd_prtype" data-validation="required" data-validation-error-msg="Please Enter Value" value='Asset' selected>Asset</label>
							<label class="radio-inline"><input type="radio" name="purordhd_prtype" data-validation="required" data-validation-error-msg="Please Enter Value" value='Others' selected>Others</label>
							<label class="radio-inline"><input type="radio" name="purordhd_prtype" data-validation="required" data-validation-error-msg="Please Enter Value" value='AssetMaintenance' selected>Asset Maintenance</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="purordhd_prdept">Purchase Department</label>
						<div class="col-md-2">
							<div class='input-group'>
								<input id="purordhd_prdept" name="purordhd_prdept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_purordno">PO No</label>
						<div class="col-md-2">
							<input id="purordhd_purordno" name="purordhd_purordno" type="text" class="form-control input-sm" frozeOnEdit hideOne rdonly>
						</div>
						
						<label class="col-md-2  control-label" for="purordhd_recno">Record No</label>
						<div class="col-md-2">
							<input id="purordhd_recno" name="purordhd_recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="purordhd_deldept">Delivery Department</label>
						<div class="col-md-2">
							<div class='input-group'>
								<input id="purordhd_deldept" name="purordhd_deldept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_reqdept">Req Dept</label>
						<div class="col-md-2">
							<div class='input-group'>
								<input id="purordhd_reqdept" name="purordhd_reqdept" type="text" maxlength="12" class="form-control input-sm text-uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_purreqno">Req No</label>
						<div class="col-md-2">
							<div class='input-group'>
								<input id="purordhd_purreqno" name="purordhd_purreqno" type="text" maxlength="12" class="form-control input-sm text-uppercase" frozeOnEdit rdonly>
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="purordhd_suppcode">Supplier Code</label>
						<div class="col-md-2">
							<div class='input-group'>
								<input id="purordhd_suppcode" name="purordhd_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_credcode">Creditor</label>
						<div class="col-md-2">
							<div class='input-group'>
								<input id="purordhd_credcode" name="purordhd_credcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_salesorder">Sales Order</label>
						<div class="col-md-2">
							<div class='input-group'>
								<input id="purordhd_salesorder" name="purordhd_salesorder" type="text" maxlength="12" class="form-control input-sm text-uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>
					
					<hr/>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="purordhd_purdate">PO Date</label>
						<div class="col-md-2">
							<input id="purordhd_purdate" name="purordhd_purdate" type="date" value="<?php echo date("Y-m-d"); ?>" maxlength="10" class="form-control input-sm" min="<?php $backday= 3; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" max="<?php echo date('Y-m-d');?>" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_expecteddate">Expected Date</label>
						<div class="col-md-2">
							<input id="purordhd_expecteddate" name="purordhd_expecteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value"  value="<?php echo date("Y-m-d"); ?>" min="<?php echo date("Y-m-d"); ?>">
						</div>
						
						<label class="col-md-2 control-label" for="termdays">Payment Terms</label>
						<div class="col-md-2">
							<input id="purordhd_termdays" name="purordhd_termdays" type="text" class="form-control input-sm" data-validation="number" frozeOnEdit hideOne value="30">
						</div>
					</div>
					
					<hr/>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="purordhd_perdisc">Discount[%]</label>
						<div class="col-md-2">
							<input id="purordhd_perdisc" name="purordhd_perdisc" type="text" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_amtdisc">Amount Discount</label>
						<div class="col-md-2">
							<input id="purordhd_amtdisc" name="purordhd_amtdisc" type="text" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_recstatus">Record Status</label>
						<div class="col-md-2">
							<input id="purordhd_recstatus" name="purordhd_recstatus" type="text" class="form-control input-sm" rdonly>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="purordhd_subamount">Sub Amount</label>
						<div class="col-md-2">
							<input id="purordhd_subamount" name="purordhd_subamount" type="text" maxlength="12" class="form-control input-sm" rdonly>
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_totamount">Total Amount</label>
						<div class="col-md-2">
							<input id="purordhd_totamount" name="purordhd_totamount" type="text" maxlength="12" class="form-control input-sm" rdonly>
						</div>
						
						<label class="col-md-2 control-label" for="purordhd_taxclaimable">Tax Claim</label>
						<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="purordhd_taxclaimable" data-validation="required" data-validation-error-msg="Please Enter Value" value='Claimable'>Yes</label><br>
							<label class="radio-inline"><input type="radio" name="purordhd_taxclaimable" data-validation="required" data-validation-error-msg="Please Enter Value"  value='Non-Claimable' selected>No</label>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label" for="purordhd_remarks">Remark</label>
							<div class="col-md-3">
								<textarea rows="5" id='purordhd_remarks' name='purordhd_remarks' class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
							</div>
							
							<div id="assetno_div" style="display: none;">
								<label class="col-md-1 control-label" for="purordhd_assetno">Asset No.</label>
								<div class="col-md-2">
									<div class='input-group'>
										<input id="purordhd_assetno" name="purordhd_assetno" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-group data_info">
						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_requestby">Prepared By</label>  
							<input id="purordhd_requestby" name="purordhd_requestby" type="text" maxlength="30" class="form-control input-sm" rdonly>
						</div>

						<!-- <div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_supportby">Support By</label>
							<input id="purordhd_supportby" name="purordhd_supportby" type="text" maxlength="30" class="form-control input-sm" rdonly>
						<i class="fa fa-info-circle my_remark" aria-hidden="true" id='support_remark_i'></i>
						</div> -->

							<div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_verifiedby">Verified By</label>  
							<input id="purordhd_verifiedby" name="purordhd_verifiedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
						<i class="fa fa-info-circle my_remark" aria-hidden="true" id='verified_remark_i'></i>
						</div>

						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_approvedby">Approved By</label>
							<input id="purordhd_approvedby" name="purordhd_approvedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
						<i class="fa fa-info-circle my_remark" aria-hidden="true" id='approved_remark_i'></i>
						</div>

							<div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_adduser">Add By</label>
							<input id="purordhd_adduser" name="purordhd_adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
						</div>
						
						<div class="col-md-2 minuspad-15">
							@if(Request::get('scope') == 'REOPEN')
								<label class="control-label" for="purordhd_cancelby">Reject User</label>
							<input id="purordhd_cancelby" name="purordhd_cancelby" type="text" maxlength="30" class="form-control input-sm" rdonly>
							<i class="fa fa-info-circle my_remark" aria-hidden="true" id='cancelled_remark_i'></i>
						@else
								<label class="control-label" for="purordhd_upduser">Last User</label>
								<input id="purordhd_upduser" name="purordhd_upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
						@endif
						</div>

						<div class="col-md-2 minuspad-15">
								<label class="control-label" for="purordhd_requestdate">Prepared Date</label>  
								<input id="purordhd_requestdate" name="purordhd_requestdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
						</div>

						<!-- <div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_supportdate">Support Date</label>
							<input id="purordhd_supportdate" name="purordhd_supportdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
						</div> -->

						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_verifieddate">Verified Date</label>  
							<input id="purordhd_verifieddate" name="purordhd_verifieddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
						</div>

						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_approveddate">Approved Date</label>
							<input id="purordhd_approveddate" name="purordhd_approveddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
						</div>

						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="purordhd_adddate">Add Date</label>
							<input id="purordhd_adddate" name="purordhd_adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
						</div>

						<div class="col-md-2 minuspad-15">
							@if(Request::get('scope') == 'REOPEN')
								<label class="control-label" for="purordhd_canceldate">Reject Date</label>
							<input id="purordhd_canceldate" name="purordhd_canceldate" type="text" maxlength="30" class="form-control input-sm" rdonly>
						@else
								<label class="control-label" for="purordhd_upddate">Update Date</label>
								<input id="purordhd_upddate" name="purordhd_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
						@endif
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class='panel panel-info'>
			<div class="panel-heading">Purchase Order Detail 
				<!-- <a class='btn btn-sm btn-default' id='add_fr_pr' style="margin-left: 30px"><span class='fa fa-plus'></span> Add From Purchase Request </a> -->
			</div>
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
				</div>

				<div class="noti"><ol></ol>
				</div>
		</div>
			
		<div id="dialog_remarks" title="Remarks">
			<div class="panel panel-default">
			<div class="panel-body">
				<textarea id='remarks2' name='remarks2' rows='6' class="form-control input-sm" style="width:100%;"></textarea>
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

		<div id="dialog_remarks_view" title="Remarks">
		  <div class="panel panel-default">
		    <div class="panel-body">
		    	<textarea id='remarks_view' name='remarks_view' readonly rows='6' class="form-control input-sm text-uppercase" style="width:100%;"></textarea>
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
	<script src="js/material/purchaseOrder/purchaseOrder.js?v=1.22"></script>
	<!-- <script src="js/material/purchaseOrder/pdfgen.js"></script> -->
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection