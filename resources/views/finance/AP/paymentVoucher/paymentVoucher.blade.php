@extends('layouts.main')

@section('title', 'Payment Voucher/Deposit')

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

#div_print_alloc{
	display: inline-block;
	position: absolute;
	right: 170px;
	top: 20px;
}
#div_print_alloc a{
	padding-right:10px;
}
#div_print_alloc span{
	float: right;
    padding-right: 10px;
}
.data_info .col-md-2.minuspad-15{
	width: 16.5% !important;
}
div#fail_msg{
  padding-left: 40px;
  padding-bottom: 10px;
  color: darkred;
}

@endsection

@section('body')

	<!-- @include('layouts.default_search_and_table') -->
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="ttype_get" name="ttype_get" type="hidden" value="{{Request::get('ttype')}}">
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

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							  	<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2" value="@if(!empty(Request::get('auditno'))){{Request::get('auditno')}}@endif">
									
								<div id="creditor_text" style="display:none" >
									<div class='input-group'>
										<input id="creditor_search" name="creditor_search" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span id="creditor_search_hb" class="help-block"></span>
								</div>

								<div id="actdate_text" class="form-inline" style="display:none">
									FROM DATE <input id="actdate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
									TO DATE <input id="actdate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
									<button type="button" class="btn btn-primary btn-sm" id="actdate_search">SEARCH</button>
								</div>
							
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
					      <option value="APPROVED">APPROVED</option>
							@elseif (Request::get('scope') == 'SUPPORT')
								<option value="PREPARED" selected>PREPARED</option>
							@elseif (Request::get('scope') == 'VERIFIED')
						      <option value="All2" selected>ALL</option>
								<option value="SUPPORT">SUPPORT</option>
								<option value="PREPARED">PREPARED</option>
							@elseif (Request::get('scope') == 'APPROVED')
								<option value="VERIFIED" selected>VERIFIED</option>
							@elseif (Request::get('scope') == 'REOPEN')
								<option value="REJECTED" selected>REJECTED</option>
								<option value="CANCELLED">CANCELLED</option>
							@elseif (Request::get('scope') == 'CANCEL')
								<option value="OPEN" selected>OPEN</option>
								<option value="REJECTED">REJECTED</option>
					      <option value="PREPARED">PREPARED</option>
					      <option value="SUPPORT">SUPPORT</option>
					      <option value="VERIFIED">VERIFIED</option>
					      <option value="APPROVED">APPROVED</option>
							@endif
				    </select>
	        </div>

					<div class="col-md-2">
				  	<label class="control-label" for="ttype">Trantype</label>  
				  	<select id="ttype" name="ttype" class="form-control input-sm">
				  	@if (Request::get('ttype') == 'PD')
			      <option value="All">ALL</option>
			      <option value="PV">Payment Voucher</option>
			      <option value="PD" selected>Payment Deposit</option>
				  	@else
			      <option value="All" selected>ALL</option>
			      <option value="PV">Payment Voucher</option>
			      <option value="PD">Payment Deposit</option>
				  	@endif
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
					}
				?>

				<div id="div_for_but_post" class="col-md-8 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<span id="error_infront" style="color: red"></span>
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>

					@if (Request::get('scope') != 'ALL' && Request::get('scope') != 'REOPEN' && Request::get('scope') != 'CANCEL')
					<button type="button" class="btn btn-danger btn-sm" id="but_cancel_jq" data-oper="reject" style="display: none;">REJECT</button>
					@endif

					<!-- <button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button> -->
					<button 
						type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="{{$scope_use}}"
						style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'PREPARED'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>

					<!-- <button type="button" class="btn btn-primary btn-sm" id="but_post_single_jq" data-oper="posted" style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button> -->

					<!-- <button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button> -->
					<!-- <button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="soft_cancel" style="display: none;">CANCEL</button> -->
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
		    <div class="panel-heading">Payment Voucher/Deposit Header
			<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
			</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>

	    <div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed" id="panel_jqGrid3">
				<b>DOCUMENT NO: </b><span id="trantype_show"></span> - <span id="pvno_show"></span><span id="ifcancel_show" style="color: red;"></span>
				<b style="padding-left: 20px;">AUDIT NO: </b><span id="auditno_show"></span><br>
				<b>CREDITOR NAME: </b><span id="suppcode_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid3_panel" ></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid3_panel" ></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 70px; top: 10px;">
					<h5>Allocation</h5>
				</div>

				<div class="pull-right" id="div_print_alloc">
					<a class='pull-right pointer text-primary' id='link_do' href="" target="_blank">GRN</a><span>|</span>
					<a class='pull-right pointer text-primary' id='link_po' href="" target="_blank">Purchase Order</a><span>|</span>
					<a class='pull-right pointer text-primary' id='link_invoice' href="" target="_blank">Invoice</a>
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
								Cancel PV
						</button>
					@endif
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
			<div class="panel-heading">Payment Voucher/Deposit Header</div>
			<div class="panel-body" style="position: relative;padding-bottom: 0px;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="apacthdr_source" name="apacthdr_source" type="hidden" value="{{$_GET['source']}}">
					<!-- <input id="apacthdr_trantype" name="apacthdr_trantype" type="hidden"> -->
					<input id="auditno" name="auditno" type="hidden">
					<input id="idno" name="idno" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_actdate">Doc Date</label>  
				  			<div class="col-md-2">
								<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>

						<label class="col-md-2 control-label" for="apacthdr_postdate">Post Date</label>  
				  			<div class="col-md-2">
								<input id="apacthdr_postdate" name="apacthdr_postdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>


				  		<label class="col-md-2 control-label" for="apacthdr_auditno">Audit No</label>  
				  			<div class="col-md-2">
				  				<input id="apacthdr_auditno" name="apacthdr_auditno" type="text" class="form-control input-sm" rdonly>
				  			</div>	
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_trantype">Transaction Type</label> 
							<div class="col-md-2">
							  	<select id="apacthdr_trantype" name=apacthdr_trantype class="form-control" data-validation="required">
							       <option value="PV">Payment Voucher</option>
							       <option value="PD">Payment Deposit</option>
							    </select>
						  	</div>

						<label class="col-md-2 control-label" for="apacthdr_document">Document No</label>  
				  			<div class="col-md-2">
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase" rdonly>
				  			</div>

						<label class="col-md-2 control-label" for="apacthdr_paymode">Paymode</label>	 
						 	<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_paymode" name="apacthdr_paymode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
					</div>

					<div class="form-group">

						<label class="col-md-2 control-label" for="apacthdr_bankcode" id="bankcode_parent">Bank Code</label>	 
						 	<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_bankcode" name="apacthdr_bankcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" >
									<a class='input-group-addon btn btn-primary' id="bankcode_dh"><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block" ></span>
						</div>	  	

						<label class="col-md-2 control-label" for="apacthdr_cheqno" id="cheqno_parent">Cheque No</label>	  
				  			<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_cheqno" name="apacthdr_cheqno" type="text" maxlength="12" class="form-control input-sm text-uppercase" >
									<a class='input-group-addon btn btn-primary' id="cheqno_dh"><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

						<label class="col-md-2 control-label" for="apacthdr_cheqdate" id="cheqdate_parent">Cheque Date</label>  
				  			<div class="col-md-2" id="apacthdr_cheqdate">
								<input id="apacthdr_cheqdate" name="apacthdr_cheqdate" type="date" maxlength="12" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
							
					</div>

					<!-- <div class="form-group">
					<label class="col-md-2 control-label" for="apacthdr_recdate">Post Date</label>  
				  			<div class="col-md-2" id="apacthdr_recdate">
								<input id="apacthdr_recdate" name="apacthdr_recdate" type="date" maxlength="12" class="form-control input-sm"  value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
			   		</div> -->

					<hr/>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Pay To (In Invoice)</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>		  	

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_bankaccno">Bank A/C No</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_bankaccno" name="apacthdr_bankaccno" type="text" class="form-control input-sm text-uppercase" maxlength="30">
				  			</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Total Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm"> 
		 					</div>

						<label class="col-md-2 control-label" for="apacthdr_pvno">PV No</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_pvno" name="apacthdr_pvno" type="text" class="form-control input-sm text-uppercase" maxlength="30" rdonly>
				  			</div>
					</div>
					<div class="panel-body">
						<div class="notiH" style="font-size: bold; color: red"><ol></ol></div>
					</div>

					<!-- <hr/>

					<input type='checkbox' name='checkbox_selection' id='checkbox_selection_'>
					<label for="checkbox_selection">Online Banking</label>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_prov_prod">Provider Product</label>	  
							<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_prov_prod" name="apacthdr_prov_prod" type="text" maxlength="12" class="form-control input-sm text-uppercase" rdonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
						<label class="col-md-2 control-label" for="apacthdr_destination">Destination</label>	  
							<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_destination" name="apacthdr_destination" type="text" maxlength="12" class="form-control input-sm text-uppercase" rdonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

						<label class="col-md-2 control-label" for="apacthdr_purp_of_trans">Purpose of Transfer</label>	  
							<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_purp_of_trans" name="apacthdr_purp_of_trans" type="text" maxlength="12" class="form-control input-sm text-uppercase" rdonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>  	
					</div>		  	 -->
					
					<button type="button" id='save' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;'>Save</button>
					<div class="form-group data_info">
						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_requestby">Prepared By</label>  
				  			<input id="apacthdr_requestby" name="apacthdr_requestby" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_supportby">Support By</label>
				  			<input id="apacthdr_supportby" name="apacthdr_supportby" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			<i class="fa fa-info-circle my_remark" aria-hidden="true" id='support_remark_i'></i>
			  			</div>

					  <div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_verifiedby">Verified By</label>  
				  			<input id="apacthdr_verifiedby" name="apacthdr_verifiedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			<i class="fa fa-info-circle my_remark" aria-hidden="true" id='verified_remark_i'></i>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_approvedby">Approved By</label>
				  			<input id="apacthdr_approvedby" name="apacthdr_approvedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			<i class="fa fa-info-circle my_remark" aria-hidden="true" id='approved_remark_i'></i>
			  			</div>

					  <div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_adduser">Add By</label>
				  			<input id="apacthdr_adduser" name="apacthdr_adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>
						
						<div class="col-md-2 minuspad-15">
							@if(Request::get('scope') == 'REOPEN')
							<label class="control-label" for="apacthdr_cancelby">Reject User</label>
				  			<input id="apacthdr_cancelby" name="apacthdr_cancelby" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			<i class="fa fa-info-circle my_remark" aria-hidden="true" id='cancelled_remark_i'></i>
				  			@else
							<label class="control-label" for="apacthdr_upduser">Last User</label>
				  			<input id="apacthdr_upduser" name="apacthdr_upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			@endif
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_requestdate">Prepared Date</label>  
				  			<input id="apacthdr_requestdate" name="apacthdr_requestdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_supportdate">Support Date</label>
				  			<input id="apacthdr_supportdate" name="apacthdr_supportdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_verifieddate">Verified Date</label>  
				  			<input id="apacthdr_verifieddate" name="apacthdr_verifieddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_approveddate">Approved Date</label>
				  			<input id="apacthdr_approveddate" name="apacthdr_approveddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="apacthdr_adddate">Add Date</label>
				  			<input id="apacthdr_adddate" name="apacthdr_adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

						<div class="col-md-2 minuspad-15">
							@if(Request::get('scope') == 'REOPEN')
							<label class="control-label" for="apacthdr_canceldate">Reject Date</label>
				  			<input id="apacthdr_canceldate" name="apacthdr_canceldate" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			@else
							<label class="control-label" for="apacthdr_upddate">Last Date</label>
				  			<input id="apacthdr_upddate" name="apacthdr_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			@endif
			  			</div>
					</div>
				</form>
			</div>
		</div>
			

		<div class='panel panel-info' id="pvpd_detail">
			<div class="panel-heading">Allocation</div>
				<div class="panel-body">
					<div id="fail_msg"></div>
					<form id='formdata2' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_c" class='col-md-12'>
							<table id="jqGrid2" class="table table-striped"></table>
						        <div id="jqGridPager2"></div>
						</div>
					</form>
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
	<script src="js/finance/AP/paymentVoucher/paymentVoucher.js?V=1.14"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>

	
@endsection