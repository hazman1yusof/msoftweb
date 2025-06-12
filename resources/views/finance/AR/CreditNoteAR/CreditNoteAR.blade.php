@extends('layouts.main')

@section('title', 'Credit Note AR')

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
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<input id="getYear" name="getYear" type="hidden"  value="{{Carbon\Carbon::now()->year}}">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>
							<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
						</div>

					  	<div class="col-md-5" style="margin-top: 4px;">
					  		<label class="control-label"></label>  
							<input style="display:none" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
							<div id="customer_text">
								<div class='input-group'>
									<input id="customer_search" name="customer_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="customer_search_hb" class="help-block"></span>
							</div>

							<div id="docudate_text" class="form-inline" style="display:none">
								FROM DATE <input id="docudate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
								TO DATE <input id="docudate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
								<button type="button" class="btn btn-primary btn-sm" id="docudate_search">SEARCH</button>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-2">
					<label class="control-label" for="Status">Status</label>  
					<select id="Status" name="Status" class="form-control input-sm">
						<option value="All" selected>ALL</option>
						<option value="Open">OPEN</option>
						<option value="Posted">POSTED</option>
						<option value="Cancelled">CANCELLED</option>
					</select>
				</div>

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

				<div id="div_for_but_post" class="col-md-10 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<span id="error_infront" style="color: red"></span>
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button 
						type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="posted"
						style="display: none;">						
						POST
					</button>

					<button type="button" class="btn btn-primary btn-sm" id="but_post_single_jq" data-oper="posted" style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>

					<!-- <button type="button" class="btn btn-default btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
					<button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="soft_cancel" style="display: none;">CANCEL</button> -->
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
			<div class="panel-heading">Credit Note Data Entry Header
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
				<b>Credit No: </b><span id="CreditNo_show"></span><br> 
				<!-- <b>CUSTOMER NAME: </b><span id="CustName_show"></span> -->
				
				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Credit Note Data Entry Detail</h5>
				</div>
			</div>

			<div id="jqGrid3_panel" class="panel-collapse collapse">
				<div class="panel-body">
					@if (strtoupper(Request::get('scope')) == 'CANCEL')
						<button 
								type="button" 
								class="btn btn-danger btn-sm" 
								id="but_post2_jq"
								data-oper="cancel"
								style="float: right;margin: 0px 20px 10px 20px;">
								Cancel Credit Note
						</button>
					@endif
					<div id="" class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
			</div>	
		</div>

		<div class="panel panel-default" style="position: relative;" id="jqGridArAlloc_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGridArAlloc_panel">
				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Allocation</h5>
				</div>
			</div>

			<div id="jqGridArAlloc_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div id="" class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGridArAlloc" class="table table-striped"></table>
						<div id="jqGridPagerArAlloc"></div>
					</div>
				</div>
			</div>	
		</div>
    </div>
	<!-- ***************End Search + table ********************* -->
	
	<input id="amount_placeholder" name="amount_placeholder" type="hidden">
	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Credit Note Header</div>
			<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="db_idno" name="db_idno" type="hidden">
					<input id="db_source" name="db_source" type="hidden">
					<input id="db_trantype" name="db_trantype" type="hidden">
					<input id="db_posteddate" name="db_posteddate" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_unallocated">Transaction Type</label> 
						<div class="col-md-2">
							<select id="db_unallocated" name="db_unallocated" class="form-control" data-validation="required">
								<option value = "1">Credit Note</option>
								<option value = "0">Credit Note Unallocated</option>
							</select>
						</div>

						<label class="col-md-3 control-label" for="db_entrydate">Date</label>  
						<div class="col-md-2">
							<input id="db_entrydate" name="db_entrydate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_debtorcode">Debtor</label>
						<div class="col-md-2">
							<div class='input-group'>
								<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-3 control-label" for="db_auditno">Credit No</label>  
						<div class="col-md-2"> 
							<input id="db_auditno" name="db_auditno" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" rdonly>
						</div>
					</div>
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="db_amount">Total Amount</label>
						<div class="col-md-2">
							<input id="db_amount" name="db_amount" type="text" maxlength="11" class="form-control input-sm" value="0.00" rdonly>
						</div>

						<label class="col-md-3 control-label" for="db_outamount">Out Amount</label>
						<div class="col-md-2">
							<input id="db_outamount" name="db_outamount" maxlength="10" class="form-control input-sm" rdonly>
						</div>
					</div>
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="db_recstatus">Record Status</label>
						<div class="col-md-2">
							<input id="db_recstatus" name="db_recstatus" maxlength="10" class="form-control input-sm" rdonly>
						</div>

						<label class="col-md-3 control-label" for="tot_alloc">Total Allocate Amount</label>
						<div class="col-md-2">
							<input id="tot_alloc" name="tot_alloc" maxlength="10" class="form-control input-sm" rdonly>
						</div>
					</div>

					<hr>
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="db_reference">Reference No.</label>  
						<div class="col-md-2">
							<input id="db_reference" name="db_reference" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
					
						<label class="col-md-3 control-label" for="db_paymode">Pay Mode</label>	 
						<div class="col-md-2">
							<div class='input-group'>
								<input id="db_paymode" name="db_paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>		
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_remark">Remarks</label> 
						<div class="col-md-7"> 
							<textarea class="form-control input-sm text-uppercase" name="db_remark" rows="3" cols="55" maxlength="400" id="db_remark" ></textarea>
						</div>
					</div>

					<hr/>

					<button type="button" id='posted_button' class='btn btn-primary btn-sm pull-right' style='margin: 0.2%;'>Posted</button>

				</form>
				
				<div class="panel-body">
				   	<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			    </div>
			</div>
		</div>
			
		<div class='panel panel-info' id="grid_dtl">
			<div class="panel-heading">Credit Note Detail</div>
			<div class="panel-body">
				<form id='formdata2' class='form-vertical' style='width:99%'>
					<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

					<div id="jqGrid2_c" class='col-md-12'>
						<table id="jqGrid2" class="table table-striped"></table>
						<div id="jqGridPager2"></div>
					</div>
				</form>
			</div>

			<!-- <div class="panel-body">
				<div class="noti" style="color:red"></div>
			</div> -->
		</div>
			
		<div class='panel panel-info' id="grid_alloc">
			<div class="panel-heading">Allocation</div>
			<div class="panel-body">
				<form id='formdataAlloc' class='form-vertical' style='width:99%'>
					<input type="hidden" id="jqGridAlloc_itemcode_refresh" name="" value="0">

					<div id="jqGridAlloc_c" class='col-md-12'>
						<table id="jqGridAlloc" class="table table-striped"></table>
						<div id="jqGridPagerAlloc"></div>
					</div>
				</form>
			</div>

			<!-- <div class="panel-body">
				<div class="noti" style="color:red"></div>
			</div> -->
		</div>
				
		<div id="dialog_remarks" title="Remarks">
			<div class="panel panel-default">
				<div class="panel-body">
					<textarea id='remarks2' name='remarks2' rows='6' class="form-control input-sm text-uppercase" style="width:100%;"></textarea>
				</div>
			</div>
		</div>
		<!-- </div> -->
	</div>

@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex', 2);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex', 3);
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
	<script src="js/finance/AR/CreditNoteAR/CreditNoteAR.js?v=1.1"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection