@extends('layouts.main')

@section('title', 'Direct Payment')

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

						<div class="col-md-5">
					  	<label class="control-label"></label>  
							<input style="display:none" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2" value="@if(!empty(Request::get('auditno'))){{Request::get('auditno')}}@endif">

							<div id="bankcode_text">
								<div class='input-group'>
									<input id="bankcode_search" name="bankcode_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="bankcode_search_hb" class="help-block"></span>
							</div>

							<div id="creditor_text" style="display:none">
								<div class='input-group'>
									<input id="creditor_search" name="creditor_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="creditor_search_hb" class="help-block"></span>
							</div>

							<div id="actdate_text" class="form-inline" style="display:none">
								FROM DATE <input id="actdate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
								TO <input id="actdate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
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
						@elseif (Request::get('scope') == 'VERIFIED')
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

				
	      <?php 
					$scope_use = 'posted';

					if(Request::get('scope') == 'ALL'){
						$scope_use = 'posted';
					}else if(Request::get('scope') == 'PREPARED'){
						$scope_use = 'posted';
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

			<div id="div_for_but_post" class="col-md-6 col-md-offset-6" style="padding-top: 20px; text-align: end;">
				
				<span id="error_infront" style="color: red"></span>
				<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
				<!-- <button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button> -->

				@if (Request::get('scope') != 'ALL' && Request::get('scope') != 'REOPEN' && Request::get('scope') != 'CANCEL')
				<button type="button" class="btn btn-danger btn-sm" id="but_cancel_jq" data-oper="reject" style="display: none;">REJECT</button>
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
		    <div class="panel-heading">Direct Payment Header
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
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel">
				<b>BANK CODE: </b><span id="bankcode_show"></span><br>
				<b>PAY TO: </b><span id="payto_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Direct Payment Detail</h5>
				</div>				
			</div>
			<div id="jqGrid3_panel" class="panel-collapse collapse">
				<div class="panel-body">
	    			<div id="" class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid3" class="table table-striped"></table>
            			<div id="jqGridPager3"></div>
    				</div>
	    		</div>
			</div>	
		</div>

</div>

	<!-- ***************End Search + table ********************* -->
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:100%' id='formdata'>
				{{ csrf_field() }}
				<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading">Direct Payment Header</div>
							<div class="panel-body">

								<input id="source" name="source" type="hidden" value="CM">
								<input id="trantype" name="trantype" value = "DP" type="hidden">
								<input id="auditno" name="auditno" type="hidden">
								<input id="idno" name="idno" type="hidden">


								<div class="form-group" style="position: relative">
						  			<label class="col-md-2 control-label" for="auditno">Audit No</label>  
						  				<div class="col-md-3">
						  					<input id="auditno" name="auditno" type="text" class="form-control input-sm" frozeOnEdit rdonly>
						  				</div>

						  			<label class="col-md-2 control-label" for="pvno">PV No</label>  
						  				<div class="col-md-3">
											<input id="pvno" name="pvno" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit rdonly>
						  				</div>
						  				<div id="recstatus"></div>
								</div>

								<div class="form-group">
						  			<label class="col-md-2 control-label" for="actdate">Payment Date</label>  
						  				<div class="col-md-3">
											<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						  				</div>

						  			<label class="col-md-2 control-label" for="paymode">Payment Mode</label>  
						  				<div class="col-md-3">
							 				<div class='input-group'>
												<input id="paymode" name="paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
												<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  				</div>
							 				<span class="help-block"></span>
		                      			</div>
					    		</div>

					    		<div class="form-group">
					    			<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
						  				<div class="col-md-3" id="bankcode_parent">
							 				<div class='input-group'>
												<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
												<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  				</div>
							 				<span id='bc' class="help-block"></span>
		                      			</div>

									<div id="chg_div" style="display:none;">
										<label class="col-md-2 control-label" for="cheqno" id="chg_label">Cheque No</label>  
											<div class="col-md-3" id="cheqno_parent">
												<div class='input-group'>
													<input id="cheqno" name="cheqno" type="text" class="form-control input-sm text-uppercase">
														<a class='input-group-addon btn btn-primary' id="cheqno_a"><span class='fa fa-ellipsis-h' ></span></a>
												</div>
												<span id='cn' class="help-block"></span>
											</div>
									</div>
					    		</div>

							    <div class="form-group">
									<div id="chq_div" style="display:none;">
										<label class="col-md-2 control-label" for="cheqdate">Cheque Date</label>  
											<div class="col-md-3">
												<input id="cheqdate" name="cheqdate" type="date"  maxlength="12" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
											</div>
									</div>

										<label class="col-md-2 control-label" for="amount">Amount</label>  
											<div class="col-md-2">
													<input id="amount" name="amount" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" rdonly>  <!--data-validation-allowing="float" -->
											</div>
							    </div>

							    <div class="form-group">
							    	<label class="col-md-2 control-label" for="payto">Pay To</label>  
								  		<div class="col-md-3">
									 		<div class='input-group'>
												<input id="payto" name="payto" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
													<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  		</div>
									 		<span class="help-block"></span>
				                      	</div>
				                      	
							        
									<label class="col-md-2 control-label" for="TaxClaimable">GST</label>  
									  <div class="col-md-3">
										<label class="radio-inline"><input type="radio" data-validation="required" name="TaxClaimable" value='Claimable'>Claimable</label><br>
										<label class="radio-inline"><input type="radio" data-validation="required" name="TaxClaimable" value='Non-Claimable' checked>Non-Claimable</label>
									  </div>
							    </div>

							    <div class="form-group">
							    	<label class="col-md-2 control-label" for="remarks">Remarks</label> 
							    		<div class="col-md-8"> 
							    		<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
							    		</div>
							    </div>
							</div>
					</div>
				</div>
			</form>

			<div class='col-md-12'>
				<div class='panel panel-info'>
					<div class="panel-heading">Direct Payment Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							
							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
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
	<script src="js/finance/CM/directPayment/directPayment.js?v=1.1"></script>
@endsection