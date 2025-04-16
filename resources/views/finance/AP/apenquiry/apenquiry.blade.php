@extends('layouts.main')

@section('title', 'AP Enquiry')

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
                <div class="panel-heading">AP Enquiry Header
					<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #518351;" id='pdfgen_excel'>
						<span class='fa fa-print'></span> Statement 
					</a>
				</div>
                    <div class="panel-body">
                        <div class='col-md-12' style="padding:0 0 15px 0">
                            <table id="jqGrid" class="table table-striped"></table>
                            <div id="jqGridPager"></div>
                        </div>
                    </div>
            </div>

			<div class="panel panel-default" id="jqGrid3_div_in" style="position: relative;">
				<div class="panel-heading">
					<b>DOCUMENT NO: </b><span id="inTrantype_show"></span> - <span id="inDocument_show"></span><br>
					<b>CREDITOR NAME: </b><span id="inSuppcode_show"></span> - <span id="inSuppname_show"></span>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
							<h5>Invoice Detail</h5>
						</div>
				</div>
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3_in" class="table table-striped"></table>
						<div id="jqGridPager3_in"></div>
					</div>
				</div>
			</div>

            <div class="panel panel-default" id="jqGrid3_div_pv" style="position: relative;">
				<div class="panel-heading">
					<b>DOCUMENT NO: </b><span id="pvTrantype_show"></span> - <span id="pvDocument_show"></span><br>
					<b>CREDITOR NAME: </b><span id="pvSuppcode_show"></span> - <span id="pvSuppname_show"></span>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
							<h5>Payment Voucher Detail</h5>
						</div>
				</div>
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3_pv" class="table table-striped"></table>
						<div id="jqGridPager3_pv"></div>
					</div>
				</div>
			</div>

			<div class="panel panel-default" id="jqGrid3_div_dn" style="position: relative;">
				<div class="panel-heading">
					<b>DOCUMENT NO: </b><span id="dnTrantype_show"></span> - <span id="dnDocument_show"></span><br>
					<b>CREDITOR NAME: </b><span id="dnSuppcode_show"></span> - <span id="dnSuppname_show"></span>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
							<h5>Debit Note Detail</h5>
						</div>
				</div>
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3_dn" class="table table-striped"></table>
						<div id="jqGridPager3_dn"></div>
					</div>
				</div>
			</div>

            <div class="panel panel-default" id="jqGrid3_div_cn" style="position: relative;">
				<div class="panel-heading">
					<b>DOCUMENT NO: </b><span id="cnTrantype_show"></span> - <span id="cnDocument_show"></span></span><br>
					<b>CREDITOR NAME: </b><span id="cnSuppcode_show"></span> - <span id="cnSuppname_show"></span>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
							<h5>Credit Note Detail</h5>
						</div>
				</div>
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3_cn" class="table table-striped"></table>
						<div id="jqGridPager3_cn"></div>
					</div>
				</div>
			</div>

			<div class="panel panel-default" style="position: relative;" id="gridAlloc_c">
				<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#gridAlloc_panel">
				<b>DOCUMENT NO: </b><span id="allocTrantype_show"></span> - <span id="allocDocument_show"></span><br>
				<b>CREDITOR NAME: </b><span id="allocSuppcode_show"></span> - <span id="allocSuppname_show"></span>

					<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
					<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
					<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
						<h5>Allocation</h5>
					</div>
				</div>
				<div id="gridAlloc_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<div class='col-md-12' style="padding:0 0 15px 0">
							<table id="gridAlloc" class="table table-striped"></table>
							<div id="jqGridPagerAlloc"></div>
						</div>
					</div>
				</div>	
			</div>  
    </div>
	<!-------------------------------- End Search + table --------------------->

	<!------------------------- AP Statement dialog search -------------------->
	<div id="statementDialog" title="Statement">
		<input id="statement" type="hidden" class="form-control input-sm" readonly>
			<div class="panel-body">
				<form class='form-horizontal' style='width:99%' id='formdata_statement'>
					<input type="hidden" name="action" >
						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label" for="Scol">Creditor From</label> 
									<div class='input-group'> 
										<input id="suppcode_from" name="suppcode_from" type="text" class="form-control input-sm" autocomplete="off" value="">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
							</div>

							<div class="col-md-6">
							<label class="control-label" for="Scol">Creditor To</label>  
								<div class='input-group'>
									<input id="suppcode_to" name="suppcode_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label" for="Scol">Date From</label>  
									<input type="date" name="datefrom" id="datefrom" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
							</div>

							<div class="col-md-6">
								<label class="control-label" for="Scol">Date To</label>  
									<input type="date" name="dateto" id="dateto" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
					</div>
				</form>
			</div>
	</div>
	
	<!--- PAYMENT VOUCHER -->
	<div id="dialogForm_pv" title="Payment Voucher">
		<div class='panel panel-info'>
			<div class="panel-heading">Payment Voucher Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata_pv'>
				
					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_actdate">Date</label>  
				  			<div class="col-md-2" id="apacthdr_actdate">
								<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>

				  		<label class="col-md-2 control-label" for="apacthdr_pvno">PV No</label>  
				  			<div class="col-md-2">
								<input id="apacthdr_pvno" name="apacthdr_pvno" type="text" class="form-control input-sm text-uppercase" maxlength="30" rdonly>
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
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  			</div>

						<label class="col-md-2 control-label" for="apacthdr_paymode">Paymode</label>	 
						 	<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_paymode" name="apacthdr_paymode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
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

					<hr/>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" ></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Pay To (In Invoice)</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>		  	

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Total Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm" data-validation="required"> 
		 					</div>
					</div>
				</form>
				<div class="panel-body">
					<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
				</div>
			</div>
		</div>
			

		<div class='panel panel-info' id="ap_enquirydetail">
			<div class="panel-heading">Payment Voucher Detail</div>
				<div class="panel-body">
					<form id='formdata2' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_pv_c" class='col-md-12'>
							<table id="jqGrid2_pv" class="table table-striped"></table>
						        <div id="jqGridPager2_pv"></div>
						</div>
					</form>
				</div>

		</div>
	</div>

	<!-- PAYMENT DEPOSIT -->
	<div id="dialogForm_pd" title="Payment Deposit" >
		<div class='panel panel-info'>
			<div class="panel-heading">Payment Deposit Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata_pd'>
				
					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_actdate">Date</label>  
				  			<div class="col-md-2" id="apacthdr_actdate">
								<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>

				  		<label class="col-md-2 control-label" for="apacthdr_pvno">PV No</label>  
				  			<div class="col-md-2">
								<input id="apacthdr_pvno" name="apacthdr_pvno" type="text" class="form-control input-sm text-uppercase" maxlength="30" rdonly>
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
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  			</div>

						<label class="col-md-2 control-label" for="apacthdr_paymode">Paymode</label>	 
						 	<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_paymode" name="apacthdr_paymode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
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

					<hr/>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" ></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Pay To (In Invoice)</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>		  	

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Total Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm" data-validation="required"> 
		 					</div>
					</div>
				</form>
				<div class="panel-body">
					<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
				</div>
			</div>
		</div>
	</div>

	<!-- INVOICE AP NOTE -->
	<div id="dialogForm_in" title="Invoice" >
		<div class='panel panel-info'>
			<div class="panel-heading">Invoice AP Header
				<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
			</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata_in'>
					{{ csrf_field() }}
					
					<input id="auditno" name="auditno" type="hidden">
					<input id="apacthdr_idno" name="apacthdr_idno" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_doctype">Doc Type</label> 
							<div class="col-md-3">
							  	<select id="apacthdr_doctype" name=apacthdr_doctype class="form-control" data-validation="required">
							       <option value="Supplier">Supplier</option>
							       <option value="Others">Others</option>
							    </select>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_auditno">Audit No</label>  
				  			<div class="col-md-3">
				  				<input id="apacthdr_auditno" name="apacthdr_auditno" type="text" class="form-control input-sm" rdonly>
				  		</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Creditor</label>	 
						 	<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_recdate">Post Date</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_recdate" name="apacthdr_recdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_actdate">Document Date</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required"  value="{{Carbon\Carbon::now()->format('Y-m-d')}}" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_document">Document No</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required">
				  			</div>

				  		<label class="col-md-2 control-label" for="apacthdr_category">Category</label>	  
				  			<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_category" name="apacthdr_category" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_deptcode">Department</label>
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_deptcode" name="apacthdr_deptcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						 	 </div>
					</div>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" ></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Invoice Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm"  data-validation="required"> 
		 					</div>

						<label class="col-md-2 control-label" for="apactdtl_outamt">Total Detail Amount</label>  
					  		<div class="col-md-3">
								<input id="apactdtl_outamt" name="apactdtl_outamt" maxlength="12" class="form-control input-sm" rdonly> 
		 					</div>
					</div>

					<button type="button" id='save' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;'>Save</button>

				</form>
				<div class="panel-body">
				<div class="noti2" style="font-size: bold; color: red"><ol></ol></div>
			</div>
			</div>
		</div>
			

		<div class='panel panel-info' id="ap_detail">
			<div class="panel-heading">Invoice AP Detail</div>
				<div class="panel-body">
					<form id='formdata2_in' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_in_c" class='col-md-12'>
							<table id="jqGrid2_in" class="table table-striped"></table>
						        <div id="jqGridPager2_in"></div>
						</div>
					</form>
				</div>

				<div class="panel-body">
					<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
				</div>
			</div>
		</div>
		</div>	

		
	</div>
	
	<!-- CREDIT NOTE -->
	<div id="dialogForm_cn" title="Credit Note" >
		<div class='panel panel-info'>
			<div class="panel-heading">Credit Note Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata_cn'>
					{{ csrf_field() }}
					<input id="auditno" name="auditno" type="hidden">
					<input id="idno" name="idno" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_actdate">Date</label>  
				  			<div class="col-md-2" id="apacthdr_actdate">
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
						<label class="col-md-2 control-label" for="apacthdr_unallocated">Transaction Type</label> 
							<div class="col-md-2">
							  	<select id="apacthdr_unallocated" name="apacthdr_unallocated" class="form-control" data-validation="required">
							       <option value = "1">Credit Note</option>
							       <option value = "0">Credit Note Unallocated</option>
							    </select>
						  	</div>

						<label class="col-md-2 control-label" for="apacthdr_document">Document No</label>  
				  			<div class="col-md-2">
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  			</div>
						
						<label class="col-md-2 control-label" for="apacthdr_recstatus">Record Status</label>  
							<div class="col-md-2">
								<input id="apacthdr_recstatus" name="apacthdr_recstatus" type="text" value='OPEN' maxlength="30" class="form-control input-sm text-uppercase" rdonly>
							</div>	

					</div>

					<hr/>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" ></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Pay To (In Invoice)</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>		  	

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Total Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm"> 
		 					</div>
						<label class="col-md-2 control-label" for="apacthdr_outamount">Out Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_outamount" name="apacthdr_outamount" maxlength="12" class="form-control input-sm"> 
		 					</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="tot_Alloc">Total Alloc <br> Amount</label>  
					  		<div class="col-md-3">
								<input id="tot_Alloc" name="tot_Alloc" maxlength="12" class="form-control input-sm" rdonly> 
		 					</div>
					</div>
				</form>
				<div class="panel-body">
					<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
				</div>
			</div>
		</div>
			
		<div class='panel panel-info' id="cn_detail">
			<div class="panel-heading">Credit Note Detail</div>
				<div class="panel-body">
					<form id='formdata2' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_cn_c" class='col-md-12'>
							<table id="jqGrid2_cn" class="table table-striped"></table>
						        <div id="jqGridPager2_cn"></div>
						</div>
					</form>
				</div>
		</div>

		<div class='panel panel-info' id="alloc_detail">
			<div class="panel-heading">Credit Note Allocation</div>
				<div class="panel-body">
					<form id='formdataAlloc' class='form-vertical' style='width:99%'>
						<div id="jqGrid_Alloc" class='col-md-12'>
							<table id="jqGridAlloc" class="table table-striped"></table>
						        <div id="jqGridPagerAllocdtl"></div>
						</div>
					</form>
				</div>
		</div>
	</div>

	<!-- CREDIT NOTE UNALLOCATED-->
	<div id="dialogForm_cna" title="Credit Note Unallocated" >
		<div class='panel panel-info'>
			<div class="panel-heading">Credit Note Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata_cna'>
					{{ csrf_field() }}
					<input id="auditno" name="auditno" type="hidden">
					<input id="idno" name="idno" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_actdate">Date</label>  
				  			<div class="col-md-2" id="apacthdr_actdate">
								<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>

				  		<label class="col-md-2 control-label" for="apacthdr_pvno">PV No</label>  
				  			<div class="col-md-2">
								<input id="apacthdr_pvno" name="apacthdr_pvno" type="text" class="form-control input-sm text-uppercase" maxlength="30">
				  			</div>

				  		<label class="col-md-2 control-label" for="apacthdr_auditno">Audit No</label>  
				  			<div class="col-md-2"> 
				  				<input id="apacthdr_auditno" name="apacthdr_auditno" type="text" class="form-control input-sm" rdonly>
				  			</div>	
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_trantype2">Transaction Type</label> 
							<div class="col-md-2">
							  	<select id="apacthdr_trantype2" name=apacthdr_trantype2 class="form-control" data-validation="required">
							       <option value = "Credit Note">Credit Note</option>
							       <option value = "Credit Note Unallocated">Credit Note Unallocated</option>
							    </select>
						  	</div>

						<label class="col-md-2 control-label" for="apacthdr_document">Document No</label>  
				  			<div class="col-md-2">
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  			</div>

						<label class="col-md-2 control-label" for="apacthdr_deptcode">Department</label>	 
						 	<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_deptcode" name="apacthdr_deptcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_paymode">Paymode</label>	 
						 	<div class="col-md-2">
							  	<div class='input-group'>
									<input id="apacthdr_paymode" name="apacthdr_paymode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>		
					</div>

					<hr/>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" ></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Pay To (In Invoice)</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>		  	

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Total Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm"> 
		 					</div>

					</div>
				</form>
				<div class="panel-body">
					<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
				</div>
			</div>
		</div>
	</div>

	<!-- DEBIT NOTE -->
	<div id="dialogForm_dn" title="Debit Note" >
		<div class='panel panel-info'>
			<div class="panel-heading">Debit Note AP Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata_dn'>
					{{ csrf_field() }}
					
					<input id="auditno" name="auditno" type="hidden">
					<input id="apacthdr_idno" name="apacthdr_idno" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_doctype">Doc Type</label> 
							<div class="col-md-3">
							  	<select id="apacthdr_doctype" name=apacthdr_doctype class="form-control" data-validation="required">
							       <option value="Debit_Note">Debit Note</option>
							    </select>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_auditno">Audit No</label>  
				  			<div class="col-md-3">
				  				<input id="apacthdr_auditno" name="apacthdr_auditno" type="text" class="form-control input-sm" rdonly>
				  		</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Creditor</label>	 
						 	<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_recdate">Post Date</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_recdate" name="apacthdr_recdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_actdate">Doc Date</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_document">Document No</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  			</div>

					</div>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" ></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Invoice Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm" > 
		 					</div>

						<label class="col-md-2 control-label" for="apacthdr_outamount">Total Detail Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_outamount" name="apacthdr_outamount" maxlength="12" class="form-control input-sm" rdonly> 
		 					</div>
					</div>
				</form>
				<div class="panel-body">
				    <div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			    </div>
			</div>
		</div>	

		<div class='panel panel-info' id="dn_detail">
			<div class="panel-heading">Debit Note Detail</div>
				<div class="panel-body">
					<form id='formdata2' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_dn_c" class='col-md-12'>
							<table id="jqGrid2_dn" class="table table-striped"></table>
						        <div id="jqGridPager2_dn"></div>
						</div>
					</form>
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

	<script src="js/finance/AP/apenquiry/apenquiry.js?v=1.1"></script>
	
@endsection