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

div#fail_msg{
  padding-left: 40px;
  padding-bottom: 10px;
  color: darkred;
}

.data_info .col-md-2.minuspad-15{
	width: 24.7% !important
}

#more {display: none;}

#gridAllo_c input[type='text'][rowid]{
	height: 30%;
	padding: 4px 12px 4px 12px;
}
#alloText{width:9%;}#alloText{width:60%;}#alloCol{width: 30%;}
#alloCol, #alloText{
	display: inline-block;
	height: 70%;
	padding: 4px 12px 4px 12px;
}
#alloSearch{
	border-style: solid;
	border-width: 0px 1px 1px 1px;
	padding-top: 5px;
	padding-bottom: 5px;
	border-radius: 0px 0px 5px 5px;
	background-color: #f8f8f8;
	border-color: #e7e7e7;
}

table#jqGrid2 a.input-group-addon.btn.btn-primary{
	padding: 2px !important;
}

.curr_td{
	display: block;
  color: darkgreen;
  font-size: 11px;
}

.orig_td{
	display: block;
  color: darkred;
  font-size: 11px;
}

@endsection

@section('body')

	<input id="session_compcode" session_compcode="deptcode" type="hidden" value="{{Session::get('compcode')}}">
	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
	<input id="reqdept" name="reqdept" type="hidden" value="{{Session::get('reqdept')}}">
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	<input id="viewonly" name="viewonly" type="hidden" value="{{Request::get('viewonly')}}">

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
								<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2" value="@if(!empty(Request::get('auditno'))){{Request::get('auditno')}}@endif">

								<div id="customer_text" style="display: none">
									<div class='input-group'>
										<input id="customer_search" name="customer_search" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span id="customer_search_hb" class="help-block"></span>
								</div>

								<div id="patmast_text" style="display: none">
									<div class='input-group'>
										<input id="patmast_search" name="patmast_search" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span id="patmast_search_hb" class="help-block"></span>
								</div>

								<div id="department_text" style="display: none;">
									<div class='input-group'>
										<input id="department_search" name="department_search" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span id="department_search_hb" class="help-block"></span>
								</div>

								<div id="docuDate_text" class="form-inline" style="display: none;">
									FROM DATE <input id="docuDate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
									TO DATE <input id="docuDate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
									<button type="button" class="btn btn-primary btn-sm" id="docuDate_search">SEARCH</button>
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
						      <option value="PREPARED">PREPARED</option>
							  	<option value="POSTED">POSTED</option>
								  <option value="Cancelled">CANCELLED</option>
								  <option value="RECOMPUTED">RECOMPUTED</option>
							  @elseif (Request::get('scope') == 'history')
						      <option value="All" selected>ALL</option>
						      <option value="OPEN">OPEN</option>
						      <option value="PREPARED">PREPARED</option>
							  	<option value="POSTED">POSTED</option>
								  <option value="Cancelled">CANCELLED</option>
								  <option value="RECOMPUTED">RECOMPUTED</option>
							  @elseif (Request::get('scope') == 'RECOMPUTED')
									<option value="POSTED">POSTED</option>
							  @elseif (Request::get('scope') == 'DELIVERED')
									<option value="PREPARED">PREPARED</option>
								@elseif (Request::get('scope') == 'REOPEN')
									<option value="CANCELLED">CANCELLED</option>
							  @elseif (Request::get('scope') == 'CANCEL')
									<option value="OPEN">OPEN</option>
						      <option value="PREPARED">PREPARED</option>
								@endif
					   </select>
		      </div>
		      
	        <div class="col-md-2" id="trandeptSearch">
	            <label class="control-label" for="trandept">Store Dept</label> 
	            <select id='storedept' class="form-control input-sm">
	                <option value="All">ALL</option>
	                @foreach($storedept as $dept_)
	                    @if($dept_->deptcode == Session::get('deptcode'))
	                    <option value="{{$dept_->deptcode}}" selected>{{$dept_->deptcode}}</option>
	                    @else
	                    <option value="{{$dept_->deptcode}}">{{$dept_->deptcode}}</option>
	                    @endif
	                @endforeach
	            </select>
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
						}else if(Request::get('scope') == 'DELIVERED'){
							$scope_use = 'delivered';
						}else if(Request::get('scope') == 'REOPEN'){
							$scope_use = 'reopen';
						}else if(Request::get('scope') == 'RECOMPUTED'){
							$scope_use = 'recomputed';
						}else if(Request::get('scope') == 'CANCEL'){
							$scope_use = 'cancel';
						}
					?>

					<div id="div_for_but_post" class="col-md-6 col-md-offset-6" style="padding-top: 20px; text-align: end;">
						
						<span id="error_infront" style="color: red"></span>
						<button style="display: none;" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
						<!-- <button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button> -->

						@if (Request::get('scope') != 'ALL' && Request::get('scope') != 'REOPEN' && Request::get('scope') != 'CANCEL')
						<!-- <button type="button" class="btn btn-danger btn-sm" id="but_cancel_jq" data-oper="reject" style="display: none;">REJECT</button> -->
						@endif

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

						<!-- <button type="button" class="btn btn-danger btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button> -->
						<!-- <button type="button" class="btn btn-danger btn-sm" id="but_soft_cancel_jq" data-oper="soft_cancel" style="display: none;">CANCEL</button> -->
					</div>

			 </fieldset> 
		</form>

		<div class="panel panel-default" id="sel_tbl_panel" style="display: none;">
    		<div class="panel-heading heading_panel_">List Of Selected Item</div>
    		<div class="panel-body">
    			<div id="sel_tbl_div" class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid_selection" class="table table-striped"></table>
    				<div id="jqGrid_selectionPager"></div>
				</div>
    		</div>
		</div>

    <div class="panel panel-default">
			<div class="panel-heading">
				@if(strtoupper(Request::get('scope')) == 'HISTORY')
					Sales Order - <span style="font-weight: bold;color: darkred;">History</span>
				@else
					Sales Order Data Entry Header
				@endif
				<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank" ><span class='fa fa-print'></span> Print Sales Invoice</a>
				<a class='pull-right pointer text-primary' id='showpdf_do' href="" target="_blank" style="padding-right: 10px;"><span class='fa fa-print'></span> Print Sales DO</a>
			</div>
			
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0" id="jqGrid_c">
					<table id="jqGrid" class="table table-striped"></table>
						<div id="jqGridPager"></div>
				</div>
			</div>
		</div>

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
				@if (strtoupper(Request::get('scope')) == 'CANCEL')
					<button 
							type="button" 
							class="btn btn-danger btn-sm" 
							id="but_post2_jq"
							data-oper="cancel"
							style="float: right;margin: 0px 20px 10px 20px;">
							Cancel Sales Order
					</button>
				@endif
	  			<div id="" class='col-md-12' style="padding:0 0 15px 0">
	        			<table id="jqGrid3" class="table table-striped"></table>
	        			<div id="jqGridPager3"></div>
					</div>
	  		</div>
	  	</div>
		</div>

		<div class="panel panel-default" style="position: relative;" id="allocation_c">
    	<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#allocation_panel">
				<b>SALES AUTO NO: </b><span id="AutoNo_alloc_show"></span><br>
				<b>CUSTOMER NAME: </b><span id="CustName_alloc_show"></span>
				
	    	<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Allocation</h5>
				</div>
			</div>

  		<div id="allocation_panel" class="panel-collapse collapse" id="jqGridAlloc_c">
    		<div class="panel-body" style="position: relative;">
					<div class="Stext2" id="allocate" style="padding:0px 0px 0px 15px;right: 10px;top:3px">
						<a class="btn-sm" role="button">Allocate</a>
					</div>
					<div class='col-md-12' style="padding:10px 0 15px 0" id>
						<table id="jqGridAlloc" class="table table-striped"></table>
						<div id="jqGridPagerAlloc"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default" style="position: relative;" id="receipt_c">
    	<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#receipt_panel">
				<b>SALES AUTO NO: </b><span id="AutoNo_r_show"></span><br>
				<b>CUSTOMER NAME: </b><span id="CustName_r_show"></span>
				
	    	<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Point of Sales Receipt</h5>
				</div>
			</div>

  		<div id="receipt_panel" class="panel-collapse collapse">
    		<div class="panel-body">
					<div class='col-md-12' style="padding:0px">
						<div class='panel panel-info'>
							<div class="panel-heading">
								Choose type of exchange
								<span>
									<button class="btn btn-success btn-sm" id="submit_receipt" type="button" style="
										position: absolute;
								    right: 30px;
								    top: 5px;"
									>
										Submit
									</button>
								</span>
							</div>
							<div class="panel-body">
							<div id="fail_msg_r"></div>
								<ul class="nav nav-tabs">
									<li class="active"><a data-toggle="tab" href="#tab-cash" form='#f_tab-cash' aria-expanded="true">Cash</a></li>
									<li><a data-toggle="tab" href="#tab-card" form='#f_tab-card'>Card</a></li>
									<li><a data-toggle="tab" href="#tab-cheque" form='#f_tab-cheque'>Cheque</a></li>
									<li><a data-toggle="tab" href="#tab-debit" form='#f_tab-debit'>Auto Debit</a></li>
									<li><a data-toggle="tab" href="#tab-forex" form='#f_tab-forex'>Forex</a></li>
								</ul>

								<div class="tab-content">
									<div id="tab-cash" class="tab-pane fade form-horizontal active in">
										<form id='f_tab-cash' autocomplete="off">
										<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
										<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CASH">
										</br>
										<div class="myformgroup">
											<label class="control-label col-md-2" for="dbacthdr_amount">Payment</label> 
									  		<div class='col-md-4'> 
												<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
											</div>

											<label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label> 
									  		<div class='col-md-4'> 
												<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2" for="dbacthdr_RCCASHbalance">Cash Balance</label> 
									  		<div class='col-md-4'> 
												<input id="dbacthdr_RCCASHbalance" name="dbacthdr_RCCASHbalance" type="text" class="form-control input-sm" value="0.00">
											</div>

											<label class="control-label col-md-2" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
									  		<div class='col-md-4'> 
												<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
											</div>
										</div>
										</form>
									</div>
									<div id="tab-card" class="tab-pane fade">
										<form id='f_tab-card' autocomplete="off">
										<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
										</br>
										<div id="g_paymodecard_c" class='col-md-4 minuspad-15'>
											<table id="g_paymodecard" class="table table-striped"></table>
											<div id="pg_paymodecard"></div>
											<hr>
											<div class="form-group">
												<label class="control-label col-md-3" for="dbacthdr_paymode">Paymode: </label> 
										  		<div class='col-md-9'> 
													<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly  data-validation="required" data-validation-error-msg="Please Enter Value" class="form-control input-sm text-uppercase">
												</div>
											</div>
										</div>
										<div class='col-md-8'>
											<div class="form-group row" style="padding:0px;margin:0px">
										  		<div class='col-md-6'> 
														<label class="control-label" for="dbacthdr_amount">Payment</label> 
														<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
													</div>
											</div>
											<div class="clearfix"></div>
											<div class="form-group">
										  		<div class='col-md-6'> 
													<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
													<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
												</div>
										  		<div class='col-md-6'> 
													<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
													<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
												</div>
											</div>
											<div class="form-group">
										  		<div class='col-md-12'>
													<label class="control-label" for="dbacthdr_reference">Reference</label> 
													<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
												</div>
											</div>
											<div class="form-group">
										  		<div class='col-md-6'>
													<label class="control-label" for="dbacthdr_authno">Authorization No.</label> 
											  		<div class=''> 
														<input id="dbacthdr_authno" name="dbacthdr_authno" type="text" class="form-control input-sm text-uppercase">
													</div>
										  		</div>
										  		<div class='col-md-6'>
													<label class="control-label" for="dbacthdr_expdate">Expiry Date</label> 
											  		<div class=''> 
														<input id="dbacthdr_expdate" name="dbacthdr_expdate" type="month" class="form-control input-sm">
													</div>
										  		</div>
											</div>
										</div>
										</form>
									</div>
									<div id="tab-cheque" class="tab-pane fade form-horizontal">
										<form id='f_tab-cheque' autocomplete="off">
										<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CHEQUE">
										</br>
										<div class="myformgroup">
											<label class="control-label col-md-2" for="dbacthdr_entrydate">Transaction Date</label> 
									  		<div class='col-md-4'> 
												<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
											</div>

											<label class="control-label col-md-2" for="dbacthdr_amount">Payment</label> 
									  		<div class='col-md-4'> 
												<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label> 
									  		<div class='col-md-4'> 
												<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
											</div>

											<label class="control-label col-md-2" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
									  		<div class='col-md-4'> 
												<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-2" for="dbacthdr_reference">Reference</label> 
									  		<div class='col-md-8'> 
												<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
											</div>
										</div>
										</form>
									</div>
									<div id="tab-debit" class="tab-pane fade">
										<form id='f_tab-debit' autocomplete="off">
										</br>
										<div id="g_paymodebank_c" class='col-md-4 minuspad-15'>
											<table id="g_paymodebank" class="table table-striped"></table>
											<div id="pg_paymodebank"></div>
											<hr>
											<div class="form-group">
												<label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label> 
										  		<div class='col-md-9'> 
													<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
												</div>
											</div>
										</div>
										<div class='col-md-8'>
											<div class="form-group">
										  		<div class='col-md-6'> 
														<label class="control-label" for="dbacthdr_entrydate">Transaction Date</label> 
														<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
													</div>
										  		<div class='col-md-6'> 
														<label class="control-label" for="dbacthdr_bankcharges">Bank Charges</label> 
														<input id="dbacthdr_bankcharges" name="dbacthdr_bankcharges" type="text" class="form-control input-sm" value="0.00">
													</div>
											</div>
											<div class="form-group row" style="padding:0px;margin:0px">
										  		<div class='col-md-6'> 
														<label class="control-label" for="dbacthdr_amount">Payment</label> 
														<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
													</div>
											</div>
											<div class="form-group">
										  		<div class='col-md-6'>
													<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
													<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
										  		</div>
										  		<div class='col-md-6'>
													<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
													<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
										  		</div>
											</div>
											<div class="form-group">
										  		<div class='col-md-12'> 
													<label class="control-label" for="dbacthdr_reference">Reference</label> 
													<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
												</div>
											</div>
										</div>
										</form>
									</div>
									<div id="tab-forex" class="tab-pane fade">
										<form id='f_tab-forex' autocomplete="off">
										<input id="dbacthdr_currency" name="dbacthdr_currency" type="hidden">
										<input id="dbacthdr_rate" name="dbacthdr_rate" type="hidden">
										<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
										</br>
										<div id="g_forex_c" class='col-md-4 minuspad-15'>
											<table id="g_forex" class="table table-striped"></table>
											<div id="pg_forex"></div>
											<hr>
											<div class="form-group">
												<label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label> 
										  		<div class='col-md-9'> 
													<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm text-uppercase" rdonly>
												</div>
											</div>
										</div>
										<div class='col-md-8'>
											<div class="myformgroup">
										  		<div class='col-md-6'>
													<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
													<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
										  		</div>
										  		<div class='col-md-6'>
													<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
													<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
										  		</div>
											</div>
											<div class="myformgroup">
												<div class='col-md-4'> 
													<label class="control-label" for="rm">Currency</label> 
													<input id="rm" name="rm" type="text" value='RM' class="form-control input-sm" rdonly>
												</div>
										  		<div class='col-md-8'> 
													<label class="control-label" for="dbacthdr_amount">Amount</label> 
													<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="myformgroup">
												<div class='col-md-4'> 
													<label class="control-label" for="curroth">Currency</label> 
													<input id="curroth" name="curroth" type="text" class="form-control input-sm text-uppercase" rdonly>
												</div>
										  		<div class='col-md-8'>
													<label class="control-label" for="dbacthdr_amount2">Amount</label> 
													<input id="dbacthdr_amount2" name="dbacthdr_amount2" type="text" class="form-control input-sm" value="0.00">
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
        
  </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Sales Order Header
				<a class='pull-right pointer text-primary' id='pdfgen2' href="" target="_blank"><span class='fa fa-print'></span> Print Sales Invoice</a>
			</div>

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
								<input id="db_deptcode" name="db_deptcode" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
								<span class="help-block"></span>
						</div>

						<label class="col-md-1 control-label" for="db_quoteno">Quote No</label>  
						<div class="col-md-2"> 
							<div class='input-group'>
								<input id="db_quoteno" name="db_quoteno" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation-error-msg="Please Enter Value" >
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>	

						<label class="col-md-1 control-label" for="db_invno">Invoice No</label>  
						<div class="col-md-2">
							<input id="db_invno" name="db_invno" type="text" class="form-control input-sm" rdonly>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_debtorcode">Customer</label>	 
						<div class="col-md-4">
							<div class='input-group'>
							<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-1 control-label" for="db_hdrtype">Bill Type</label>  
						<div class="col-md-2"> 
							<div class='input-group'>
								<input id="db_hdrtype" name="db_hdrtype" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>			

						<label class="col-md-1 control-label" for="db_entrydate">Document Date</label>  
						<div class="col-md-2">
							<input id="db_entrydate" name="db_entrydate" type="date" maxlength="100" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value"  value="{{Carbon\Carbon::now()->format('Y-m-d')}}" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>				
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_mrn">HUKM MRN</label>	 
						<div class="col-md-4">
							<div class='input-group'>
								<input id="db_mrn" name="db_mrn" type="text" maxlength="100" class="form-control input-sm text-uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-1 control-label" for="db_posteddate">Posted Date</label>  
						<div class="col-md-2">
							<input id="db_posteddate" name="db_posteddate" type="text" maxlength="100" class="form-control input-sm" max="<?php echo date("Y-m-d"); ?>" rdonly>
						</div>

						<label class="col-md-1 control-label" for="db_orderno">Order No</label>  
						<div class="col-md-2"> 
							<input id="db_orderno" name="db_orderno" type="text" class="form-control input-sm text-uppercase" >
						</div>
					</div>

					<div class="form-group">	
						<label class="col-md-2 control-label" for="db_doctorcode">Doctor</label>	 
						<div class="col-md-4">
							<div class='input-group'>
								<input id="db_doctorcode" name="db_doctorcode" type="text" maxlength="100" class="form-control input-sm text-uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-1 control-label" for="db_termdays">Term</label>  
						<div class="col-md-1">
							<input id="db_termdays" name="db_termdays" type="text" value ="30" class="form-control input-sm">
						</div>

						<div class="col-md-1">
							<select class="form-control col-md-3" id='db_termmode' name='db_termmode' data-validation="required" data-validation-error-msg="Please Enter Value">
								<option value='DAYS' selected>DAYS</option>
								<option value='MONTH'>MONTH</option>
								<option value='YEAR'>YEAR</option>
							</select> 
						</div>	
						
						<label class="col-md-1 control-label" for="db_auditno">Auto No</label>  
						<div class="col-md-2"> 
							<input id="db_auditno" name="db_auditno" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" rdonly>
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
							<input id="db_podate" name="db_podate" type="date" maxlength="100" class="form-control input-sm" value="" max="<?php echo date("Y-m-d"); ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_amount">Total Amount</label>
						<div class="col-md-2">
							<input id="db_amount" name="db_amount" type="text" maxlength="100" class="form-control input-sm" value="0.00" rdonly>
						</div>

						<label class="col-md-3 control-label" for="db_recstatus">Record Status</label>  
						<div class="col-md-2">
								<input id="db_recstatus" name="db_recstatus" maxlength="100" class="form-control input-sm" rdonly>
						</div>

						<button type="button" id='save' class='btn btn-info btn-sm' style='margin: 0.2%;'>Save</button>
					</div>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_remark">Remarks</label> 
						<div class="col-md-6"> 
						<textarea class="form-control input-sm text-uppercase" name="db_remark" rows="5" cols="55" maxlength="400" id="db_remark" ></textarea>
						</div>
					</div>

					<div class="noti2" style="font-size: bold; color: red"><ol></ol></div>

					<div class="form-group data_info">
						<div class="col-md-2 minuspad-15">
						<label class="control-label" for="db_preparedby">Prepared By</label>  
			  			<input id="db_preparedby" name="db_preparedby" type="text" maxlength="100" class="form-control input-sm" rdonly>
		  			</div>
		    		<div class="col-md-2 minuspad-15">
							<label class="control-label" for="db_approvedby">Delivered By</label>  
			  			<input id="db_approvedby" name="db_approvedby" type="text" maxlength="100" class="form-control input-sm" rdonly>
		  			</div>
		    		<div class="col-md-2 minuspad-15">
							<label class="control-label" for="db_adduser">Add User</label>  
			  			<input id="db_adduser" name="db_adduser" type="text" maxlength="100" class="form-control input-sm" rdonly>
		  			</div>
		    		<div class="col-md-2 minuspad-15">
							@if(Request::get('scope') == 'REOPEN')
								<label class="control-label" for="db_cancelby">Reject User</label>
				  			<input id="db_cancelby" name="db_cancelby" type="text" maxlength="100" class="form-control input-sm" rdonly>
				  			<i class="fa fa-info-circle my_remark" aria-hidden="true" id='cancelled_remark_i'></i>
			  			@else
								<label class="control-label" for="db_upduser">Last User</label>  
				  			<input id="db_upduser" name="db_upduser" type="text" maxlength="100" class="form-control input-sm" rdonly>
				  		@endif
		  			</div>
		  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="db_prepareddate">Prepared Date</label>
			  			<input id="db_prepareddate" name="db_prepareddate" type="text" maxlength="100" class="form-control input-sm" rdonly>
		  			</div>
		  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="db_approveddate">Delivered Date</label>
			  			<input id="db_approveddate" name="db_approveddate" type="text" maxlength="100" class="form-control input-sm" rdonly>
				  		<i class="fa fa-info-circle my_remark" aria-hidden="true" id='approved_remark_i'></i>
		  			</div>				
		  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="db_adddate">Add Date</label>
			  			<input id="db_adddate" name="db_adddate" type="text" maxlength="100" class="form-control input-sm" rdonly>
		  			</div>
		  			<div class="col-md-2 minuspad-15">
							@if(Request::get('scope') == 'REOPEN')
								<label class="control-label" for="db_canceldate">Reject Date</label>
				  			<input id="db_canceldate" name="db_canceldate" type="text" maxlength="100" class="form-control input-sm" rdonly>
			  			@else
								<label class="control-label" for="db_upddate">Last Update</label>
				  			<input id="db_upddate" name="db_upddate" type="text" maxlength="100" class="form-control input-sm" rdonly>
				  		@endif
		  			</div>				    	
					</div>					
				</form>
			</div>
		</div>

		<div class='panel panel-info'>
			<div class="panel-heading">Sales Order Detail</div>
				<div class="panel-body">
					<div id="fail_msg"></div>
					<form id='formdata2' class='form-vertical' style='width:99%' autocomplete="off">
						<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

						<div id="jqGrid2_c" class='col-md-12' style="overflow-y: hidden;overflow-x: hidden;height: calc(100vh - 80px);">
							<table id="jqGrid2" class="table table-striped"></table>
				      <div id="jqGridPager2"></div>
						</div>
					</form>
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

	<div id="dialog_new_patient" title="Patient Form">
	  <div class="panel panel-default">
	    <div class="panel-body" style="position: relative;padding-bottom: 0px !important">
				<form class='form-horizontal' style='width:99%' id='formdata_new_patient'>
					{{ csrf_field() }}
					<input type="hidden" id="np_idno" name="np_idno">
					<div class="form-group">
						<label class="col-md-2 control-label" for="np_newmrn">HUKM MRN</label>	 
						<div class="col-md-4">
								<input id="np_newmrn" name="np_newmrn" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
						<label class="col-md-2 control-label" for="np_name">Name</label>	 
						<div class="col-md-4">
								<input id="np_name" name="np_name" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="np_address1">Address 1</label>	 
						<div class="col-md-10">
								<input id="np_address1" name="np_address1" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="np_address2">Address 2</label>	 
						<div class="col-md-10">
								<input id="np_address2" name="np_address2" type="text" maxlength="100" class="form-control input-sm text-uppercase">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="np_address3">Address 3</label>	 
						<div class="col-md-10">
								<input id="np_address3" name="np_address3" type="text" maxlength="100" class="form-control input-sm text-uppercase">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="np_postcode">Postcode</label>	 
						<div class="col-md-4">
								<input id="np_postcode" name="np_postcode" type="text" maxlength="100" class="form-control input-sm text-uppercase">
						</div>
						<label class="col-md-2 control-label" for="np_postcode">I/C</label>	 
						<div class="col-md-4">
								<input id="np_newic" name="np_newic" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
					</div>
				</form>
	    </div>
	  </div>
	</div>

	<div id="dialog_new_customer" title="Customer Form">
	  <div class="panel panel-default">
	    <div class="panel-body" style="position: relative;padding-bottom: 0px !important">
				<form class='form-horizontal' style='width:99%' id='formdata_new_customer'>
					{{ csrf_field() }}
					<input type="hidden" id="nc_idno" name="nc_idno">
					<div class="form-group">
						<label class="col-md-2 control-label" for="nc_debtorcode">Debtorcode</label>	 
						<div class="col-md-5">
								<input id="nc_debtorcode" name="nc_debtorcode" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
						<label class="col-md-1 control-label" for="nc_debtortype">Class</label>	 
						<div class="col-md-4">
								<input id="nc_debtortype" name="nc_debtortype" type="text" maxlength="100" value="PT" class="form-control input-sm text-uppercase" readonly >
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="nc_name">Name</label>	 
						<div class="col-md-10">
								<input id="nc_name" name="nc_name" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="nc_address1">Address 1</label>	 
						<div class="col-md-10">
								<input id="nc_address1" name="nc_address1" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="nc_address2">Address 2</label>	 
						<div class="col-md-10">
								<input id="nc_address2" name="nc_address2" type="text" maxlength="100" class="form-control input-sm text-uppercase">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="nc_address3">Address 3</label>	 
						<div class="col-md-10">
								<input id="nc_address3" name="nc_address3" type="text" maxlength="100" class="form-control input-sm text-uppercase">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="nc_postcode">Postcode</label>	 
						<div class="col-md-4">
								<input id="nc_postcode" name="nc_postcode" type="text" maxlength="100" class="form-control input-sm text-uppercase">
						</div>
					</div>
				</form>
	    </div>
	  </div>
	</div>

	<div id="allocateDialog" title="Create Allocation">
		<form id='formallo'>
			<input id="AlloAuditno" type="hidden" class="form-control input-sm" readonly>
			<div class='col-md-9'>
				<div class="col-md-6">
					<label class="control-label">Document Type</label>
					<input id="AlloDtype" type="text" class="form-control input-sm" readonly>
					<span class="help-block" id="AlloDtype2"></span>
				</div>

				<div class="col-md-6">
					<label class="control-label">Document No.</label>
					<input id="AlloDno" type="text" class="form-control input-sm" readonly>
				</div>

				<div class="col-md-12">
					<!-- <label class="control-label">Debtor</label> -->
					<!-- <input id="AlloDebtor" type="text" class="form-control input-sm" readonly> -->
					<label class="control-label" for="AlloDebtor">Debtor</label>  
		  			<div class='input-group'>
						<input id="AlloDebtor" name="AlloDebtor" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
		  			</div>
					<span class="help-block" id="AlloDebtor2"></span>
				</div>

				<div class="col-md-12">
					<label class="control-label">Payer</label>
					<input id="AlloPayer" type="text" class="form-control input-sm" readonly>
					<span class="help-block" id="AlloPayer2"></span>
				</div>

				<div class="col-md-6">
					<label class="control-label">Document Amount</label>
					<input id="AlloAmt" type="text" class="form-control input-sm" readonly>
				</div>

				<div class="col-md-6">
					<label class="control-label">Document O/S</label>
					<input id="AlloOutamt" type="text" class="form-control input-sm" readonly>
				</div>
			</div>

			<div class='col-md-3'>
				
					<div class="col-md-12"><hr>
						<label class="control-label">Balance After Allocate</label>
						<input id="AlloBalance" type="text" class="form-control input-sm" readonly>
					</div>

					<div class="col-md-12">
						<label class="control-label">Total Allocate</label>
						<input id="AlloTotal" type="text" class="form-control input-sm" readonly><hr>
					</div>
			</div>
		</form>

		<div class='col-md-12' id='gridAllo_c' style="padding:0">
			<hr>
	        <table id="gridAllo" class="table table-striped"></table>
	        <div id="pagerAllo"></div>
	    </div>

		<div class="col-md-10 col-md-offset-1" id="alloSearch">
			<label class="control-label" id='alloLabel'>Search</label>
			<input id="alloText" type="text" class="form-control input-sm">
			<select class="form-control" id="alloCol">
				<option value="invno" >Invoice No</option>
				<option value="auditno" >Audit No</option>
				<option value="mrn" >MRN</option>
				<option value="recptno" >Document No</option>
				<option value="newic" >New IC</option>
				<option value="staffid" >Staff ID</option>
				<option value="batchno" >Batch No</option>
			</select>
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

		<script src="js/finance/SalesOrder/SalesOrder.js?v=1.38"></script>
		<script src="plugins/pdfmake/pdfmake.min.js"></script>
		<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection