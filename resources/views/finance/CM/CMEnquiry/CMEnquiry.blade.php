@extends('layouts.main')

@section('title', 'CM Enquiry')

@section('style')
	.num{
		width:20px;
	}
	.mybtn{
		float: right;
		display: none;
	}
	.bg-primary .mybtn{
		display:block;
	}

	input.uppercase {
  		text-transform: uppercase;
	}

@endsection

@section('body')

	<!-------------------------------- Search + table ---------------------->

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
							<input style="display:none" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">

							<div id="creditor_text">
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
            </fieldset> 
		</form>    

            <div class="panel panel-default">
                <div class="panel-heading">CM Enquiry Header</div>
                <div class="panel-body">
                    <div class='col-md-12' style="padding:0 0 15px 0">
                        <table id="jqGrid" class="table table-striped"></table>
                        <div id="jqGridPager"></div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" id="jqGrid3_div_ft" style="display:none;">
			    <div class="panel-heading">Bank Transfer To</div>
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3_ft" class="table table-striped"></table>
						<div id="jqGridPager3_ft"></div>
					</div>
				</div>
			</div>

            <div class="panel panel-default" id="jqGrid3_div_dp" style="display:none;">
			    <div class="panel-heading">Bank Transfer To</div>
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3_dp" class="table table-striped"></table>
						<div id="jqGridPager3_dp"></div>
					</div>
				</div>
			</div>

			<div class="panel panel-default" id="jqGrid3_div_ca" style="display:none;">
			    <div class="panel-heading">Credit Transaction Detail</div>
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3_ca" class="table table-striped"></table>
						<div id="jqGridPager3_ca"></div>
					</div>
				</div>
			</div>

			<div class="panel panel-default" id="jqGrid3_div_da" style="display:none;">
			    <div class="panel-heading">Debit Transaction Detail</div>
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3_da" class="table table-striped"></table>
						<div id="jqGridPager3_da"></div>
					</div>
				</div>
			</div>

		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>

	<!--- BANK TRANSFER -->
    <div id="dialogForm_ft" title="Bank Transfer" >
	    <form class='form-horizontal' style='width:100%' id="formdata_ft">
			{{ csrf_field() }}
				<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading"><b>CREDIT</b></div>
							<div class="panel-body">

								<div class="prevnext btn-group pull-right"></div>
									<input id="source" name="source" type="hidden">
									<input id="trantype" name="trantype" type="hidden">
									<input type="hidden" name="auditno" id="auditno"></ins>

									<div class="form-group">
										<label class="col-md-2 control-label" for="auditno">Audit No</label>  
											<div class="col-md-3">
												<input id="auditno" name="auditno" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit rdonly>
											</div>
									
										<label class="col-md-2 control-label" for="pvno">Payment No</label>  
									  		<div class="col-md-3">
									  			<input id="pvno" name="pvno" type="text" maxlength="40" class="form-control input-sm text-uppercase" rdonly>
									  		</div>
					                </div>
					                
									<div class="form-group">
										<label class="col-md-2 control-label" for="actdate">Payment Date</label>  
											<div class="col-md-3">
										  		<div class='input-group'>
													<input id="actdate" name="actdate" type="date"  maxlength="30"  class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>"> 
										  		</div>
											</div>
										
										<label class="col-md-2 control-label" for="paymode">Payment Mode</label>  
											<div class="col-md-3">
										  		<div class='input-group'>
													<input id="paymode" name="paymode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required"/>
										  			<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  		</div>
										   		<span class="help-block"></span>
											</div>
									</div>
					                
									<div class="form-group">
										<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
											<div class="col-md-3" id="bankcode_parent">
										 		<div class='input-group'>
													<input id="bankcode" name="bankcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required"/>
													<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  		</div>
												<span id='bc' class="help-block"></span>
											</div>
										
									  	<label class="col-md-2 control-label" for="cheqno">Cheque No</label>  
						  					<div class="col-md-3" id="cheqno_parent">
							 					<div class='input-group'>
													<input id="cheqno" name="cheqno" type="text" class="form-control input-sm text-uppercase">
													<a class='input-group-addon btn btn-primary' id="cheqno_a"><span class='fa fa-ellipsis-h' ></span></a>
							  					</div>
							 					<span id='cn' class="help-block"></span>
		                      				</div>
					    			</div>


									<div class="form-group">
										<label class="col-md-2 control-label" for="cheqdate">Cheque Date</label>  
											<div class="col-md-3">
										  		<div class='input-group'>
													<input id="cheqdate" name="cheqdate" type="date" maxlength="30"  class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
										  		</div>
										 	</div>

										<label class="col-md-2 control-label" for="amount">Amount</label>  
											<div class="col-md-3">
												<input id="amount" name="amount" type="text" maxlength="30" class="form-control input-sm" data-validation="required"/>
											</div>
									</div>
								</div>
							</div>
						</div>


				<div class='col-md-12'>
					<div class='panel panel-info' id='ft_detail'>
						<div id="detail" class="panel-heading"><b>DEBIT</b></div>
							<div class="panel-body">

								<div class="form-group">
									<label class="col-md-2 control-label" for="payto">Bank Code</label>  
										<div class="col-md-3">
										  <div class='input-group'>
											<input id="payto" name="payto" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required"/>
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  </div>
										  <span class="help-block"></span>
										</div>		
								</div>

								<div class="form-group">
										<label class="col-md-2 control-label" for="remarks">Remarks</label>  
											<div class="col-md-8">
												<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="300" id="remarks"></textarea>
											</div>
								</div>	

							</div>
						</div>
					</div>
				</div>

			</form>
		</div>

    <!--- DIRECT PAYMENT -->
	<div id="dialogForm_dp" title="Direct Payment">
		<div class='panel panel-info'>
			<div class="panel-heading">Direct Payment Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:100%' id='formdata_dp'>

					<div class="form-group">
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
								<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						  	</div>

						<label class="col-md-2 control-label" for="paymode">Payment Mode</label>  
						  	<div class="col-md-3">
							 	<div class='input-group'>
									<input id="paymode" name="paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" >
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							 	<span class="help-block"></span>
		                    </div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
							<div class="col-md-3" id="bankcode_parent">
								<div class='input-group'>
									<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id='bc' class="help-block"></span>
							</div>

						<label class="col-md-2 control-label" for="cheqno">Cheque No</label>  
							<div class="col-md-3" id="cheqno_parent">
								<div class='input-group'>
									<input id="cheqno" name="cheqno" type="text" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary' id="cheqno_a"><span class='fa fa-ellipsis-h' ></span></a>
								</div>
								<span id='cn' class="help-block"></span>
							</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cheqdate">Cheque Date</label>  
							<div class="col-md-3">
								<input id="cheqdate" name="cheqdate" type="date"  maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
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
									<input id="payto" name="payto" type="text" class="form-control input-sm text-uppercase" data-validation="required"><!---->
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
							
						
						<label class="col-md-2 control-label" for="TaxClaimable">GST</label>  
							<div class="col-md-3">
							<label class="radio-inline"><input type="radio" data-validation="required" name="TaxClaimable" value='Claimable'>Claimable</label><br>
							<label class="radio-inline"><input type="radio" data-validation="required" name="TaxClaimable" value='Non-Claimable'>Non-Claimable</label>
							</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="remarks">Remarks</label> 
							<div class="col-md-8"> 
							<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" ></textarea>
							</div>
					</div>
				</form>
			</div>
		</div>

		<div class='panel panel-info'>
			<div class="panel-heading">Direct Payment Detail</div>
				<div class="panel-body">
					<form id='formdata2_dp' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_dp_c" class='col-md-12'>
							<table id="jqGrid2_dp" class="table table-striped"></table>
							<div id="jqGridPager2_dp"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

    <!--- CREDIT -->
	<div id="dialogForm_ca" title="Credit Transaction" >
		<div class='panel panel-info'>
			<div class="panel-heading">Credit Transaction Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:100%' id='formdata_ca'>

					<div class="form-group">
						<label class="col-md-2 control-label" for="auditno">Audit No</label>  
							<div class="col-md-3">
								<input id="auditno" name="auditno" type="text" class="form-control input-sm text-uppercase" frozeOnEdit rdonly>
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
								<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required"  data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
							</div>

					
						<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
							<div class="col-md-3">
								<div class='input-group'>
									<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
					</div>

					<div class="form-group">
							<label class="col-md-2 control-label" for="amount">Amount</label>  
							<div class="col-md-3">
									<input id="amount" name="amount" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" rdonly>  <!--data-validation-allowing="float" -->
							</div>

						<label class="col-md-2 control-label" for="TaxClaimable">GST</label>  
							<div class="col-md-3">
								<label class="radio-inline"><input type="radio" name="TaxClaimable" data-validation="required" value='Claimable'>Claimable</label><br>
								<label class="radio-inline"><input type="radio" name="TaxClaimable" data-validation="required" value='Non-Claimable'>Non-Claimable</label>
							</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="refsource">Reference</label>  
							<div class="col-md-8">
								<input id="refsource" class="form-control input-sm text-uppercase" name="refsource" rows="1" cols="55" maxlength="100" id="remarks">
							</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="remarks">Remarks</label> 
							<div class="col-md-8"> 
								<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
							</div>
					</div>
				</form>    
			</div>
		</div>	


		<div class='panel panel-info'>
			<div class="panel-heading">Credit Transaction Detail</div>
				<div class="panel-body">
					<form id='formdata2_ca' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_ca_c" class='col-md-12'>
							<table id="jqGrid2_ca" class="table table-striped"></table>
							<div id="jqGridPager2_ca"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!--- DEBIT -->
	<div id="dialogForm_da" title="Debit Transaction" >
		<div class='panel panel-info'>
			<div class="panel-heading">Credit Transaction Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:100%' id='formdata_da'>

					<div class="form-group">
						<label class="col-md-2 control-label" for="auditno">Audit No</label>  
							<div class="col-md-3">
								<input id="auditno" name="auditno" type="text" class="form-control input-sm text-uppercase" frozeOnEdit rdonly>
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
								<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required"  data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
							</div>

					
						<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
							<div class="col-md-3">
								<div class='input-group'>
									<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
					</div>

					<div class="form-group">
							<label class="col-md-2 control-label" for="amount">Amount</label>  
							<div class="col-md-3">
									<input id="amount" name="amount" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" rdonly>  <!--data-validation-allowing="float" -->
							</div>

						<label class="col-md-2 control-label" for="TaxClaimable">GST</label>  
							<div class="col-md-3">
								<label class="radio-inline"><input type="radio" name="TaxClaimable" data-validation="required" value='Claimable'>Claimable</label><br>
								<label class="radio-inline"><input type="radio" name="TaxClaimable" data-validation="required" value='Non-Claimable'>Non-Claimable</label>
							</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="refsource">Reference</label>  
							<div class="col-md-8">
								<input id="refsource" class="form-control input-sm text-uppercase" name="refsource" rows="1" cols="55" maxlength="100" id="remarks">
							</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="remarks">Remarks</label> 
							<div class="col-md-8"> 
								<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
							</div>
					</div>
				</form>    
			</div>
		</div>	


		<div class='panel panel-info'>
			<div class="panel-heading">Debit Transaction Detail</div>
				<div class="panel-body">
					<form id='formdata2_da' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_da_c" class='col-md-12'>
							<table id="jqGrid2_da" class="table table-striped"></table>
							<div id="jqGridPager2_da"></div>
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

	<script src="js/finance/CM/CMEnquiry/CMEnquiry.js"></script>
	
@endsection