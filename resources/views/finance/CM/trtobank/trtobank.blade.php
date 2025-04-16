@extends('layouts.main')

@section('title', 'Transfer to Bank')

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
.ui-dialog-buttonpane {
    margin-bottom: 5px;
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
						</div>
		      </div>
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
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Payment Voucher/Deposit Header</div>
			<div class="panel-body" style="position: relative;padding-bottom: 0px;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="auditno" name="auditno" type="hidden">
					<!-- <input id="idno" name="idno" type="hidden"> -->

					<div class="form-group">
						<label class="col-md-2 control-label" for="actdate">Doc Date</label>  
				  			<div class="col-md-2">
								<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>

						<label class="col-md-2 control-label" for="postdate">Post Date</label>  
				  			<div class="col-md-2">
								<input id="postdate" name="postdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>


				  		<label class="col-md-2 control-label" for="auditno">Audit No</label>  
				  			<div class="col-md-2">
				  				<input id="auditno" name="auditno" type="text" class="form-control input-sm" rdonly>
				  			</div>	
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="trantype">Transaction Type</label> 
							<div class="col-md-2">
							  	<select id="trantype" name=trantype class="form-control" data-validation="required">
							       <option value="PV">Payment Voucher</option>
							       <option value="PD">Payment Deposit</option>
							    </select>
						  	</div>

						<label class="col-md-2 control-label" for="document">Document No</label>  
				  			<div class="col-md-2">
								<input id="document" name="document" type="text" maxlength="30" class="form-control input-sm text-uppercase" rdonly>
				  			</div>

						<label class="col-md-2 control-label" for="paymode">Paymode</label>	 
						 	<div class="col-md-2">
							  	<div class='input-group'>
									<input id="paymode" name="paymode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
					</div>

					<div class="form-group">

						<label class="col-md-2 control-label" for="bankcode" id="bankcode_parent">Bank Code</label>	 
						 	<div class="col-md-2">
							  	<div class='input-group'>
									<input id="bankcode" name="bankcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" >
									<a class='input-group-addon btn btn-primary' id="bankcode_dh"><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block" ></span>
						</div>	  	

						<label class="col-md-2 control-label" for="cheqno" id="cheqno_parent">Cheque No</label>	  
				  			<div class="col-md-2">
							  	<div class='input-group'>
									<input id="cheqno" name="cheqno" type="text" maxlength="12" class="form-control input-sm text-uppercase" >
									<a class='input-group-addon btn btn-primary' id="cheqno_dh"><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

						<label class="col-md-2 control-label" for="cheqdate" id="cheqdate_parent">Cheque Date</label>  
				  			<div class="col-md-2" id="cheqdate">
								<input id="cheqdate" name="cheqdate" type="date" maxlength="12" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
							
					</div>

					<!-- <div class="form-group">
					<label class="col-md-2 control-label" for="recdate">Post Date</label>  
				  			<div class="col-md-2" id="recdate">
								<input id="recdate" name="recdate" type="date" maxlength="12" class="form-control input-sm"  value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
			   		</div> -->

					<hr/>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="suppcode">Pay To (In Invoice)</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="suppcode" name="suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
						<label class="col-md-2 control-label" for="payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="payto" name="payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					</div>		  	

					<div class="form-group">
						<label class="col-md-2 control-label" for="bankaccno">Bank A/C No</label>  
				  			<div class="col-md-3">
								<input id="bankaccno" name="bankaccno" type="text" class="form-control input-sm text-uppercase" maxlength="30">
				  			</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="amount">Total Amount</label>  
					  		<div class="col-md-3">
								<input id="amount" name="amount" maxlength="12" class="form-control input-sm"> 
		 					</div>

						<label class="col-md-2 control-label" for="pvno">PV No</label>  
				  			<div class="col-md-3">
								<input id="pvno" name="pvno" type="text" class="form-control input-sm text-uppercase" maxlength="30" rdonly>
				  			</div>
					</div>
					<div class="panel-body">
						<div class="notiH" style="font-size: bold; color: red"><ol></ol></div>
					</div>

					<!-- <hr/>

					<input type='checkbox' name='checkbox_selection' id='checkbox_selection_'>
					<label for="checkbox_selection">Online Banking</label>

					<div class="form-group">
						<label class="col-md-2 control-label" for="prov_prod">Provider Product</label>	  
							<div class="col-md-2">
							  	<div class='input-group'>
									<input id="prov_prod" name="prov_prod" type="text" maxlength="12" class="form-control input-sm text-uppercase" rdonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>
					
						<label class="col-md-2 control-label" for="destination">Destination</label>	  
							<div class="col-md-2">
							  	<div class='input-group'>
									<input id="destination" name="destination" type="text" maxlength="12" class="form-control input-sm text-uppercase" rdonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

						<label class="col-md-2 control-label" for="purp_of_trans">Purpose of Transfer</label>	  
							<div class="col-md-2">
							  	<div class='input-group'>
									<input id="purp_of_trans" name="purp_of_trans" type="text" maxlength="12" class="form-control input-sm text-uppercase" rdonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>  	
					</div>		  	 -->
					
					<div class="form-group data_info">
						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="requestby">Prepared By</label>  
				  			<input id="requestby" name="requestby" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="supportby">Support By</label>
				  			<input id="supportby" name="supportby" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

					  <div class="col-md-2 minuspad-15">
							<label class="control-label" for="verifiedby">Verified By</label>  
				  			<input id="verifiedby" name="verifiedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="approvedby">Approved By</label>
				  			<input id="approvedby" name="approvedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

					  <div class="col-md-2 minuspad-15">
							<label class="control-label" for="adduser">Add By</label>
				  			<input id="adduser" name="adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>
						
						<div class="col-md-2 minuspad-15">
							@if(Request::get('scope') == 'REOPEN')
							<label class="control-label" for="cancelby">Reject User</label>
				  			<input id="cancelby" name="cancelby" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			@else
							<label class="control-label" for="upduser">Last User</label>
				  			<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			@endif
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="requestdate">Prepared Date</label>  
				  			<input id="requestdate" name="requestdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="supportdate">Support Date</label>
				  			<input id="supportdate" name="supportdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="verifieddate">Verified Date</label>  
				  			<input id="verifieddate" name="verifieddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

			  			<div class="col-md-2 minuspad-15">
							<label class="control-label" for="approveddate">Approved Date</label>
				  			<input id="approveddate" name="approveddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

						<div class="col-md-2 minuspad-15">
							<label class="control-label" for="adddate">Add Date</label>
				  			<input id="adddate" name="adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
			  			</div>

						<div class="col-md-2 minuspad-15">
							@if(Request::get('scope') == 'REOPEN')
							<label class="control-label" for="canceldate">Reject Date</label>
				  			<input id="canceldate" name="canceldate" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			@else
							<label class="control-label" for="upddate">Last Date</label>
				  			<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
				  			@endif
			  			</div>
					</div>
				</form>
			</div>
		</div>
			

		<!-- <div class='panel panel-info' id="pvpd_detail">
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
		</div> -->
	</div>

	<div id="trtobank_form" title="Transfer to Bank">
		<form class='form-horizontal' style='width:99%' id='trtobank_formdata'>
			<input type="hidden" name="idno" id="idno">
		 	<div class="col-md-4">
					<label>Bank Code</label>	 
					<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" readonly>
			  	<span class="help-block" ></span>
			</div>
		 	<div class="col-md-8">
					<label>Bank Name</label>	 
					<input id="bankname" name="bankname" type="text" class="form-control input-sm text-uppercase" readonly>
		 	</div>

		 	<div class="col-md-4">
					<label>Cheque No.</label>
					<input id="cheqno" name="cheqno" type="text" class="form-control input-sm text-uppercase" readonly>
		 	</div>
		 	<div class="col-md-4"></div>
		 	<div class="col-md-4"></div>

		 	<div class="col-md-12" style="
				 	padding: 10px;
			    margin: 15px;
			    border: solid 1px darkgrey;
			    border-radius: 5px;
			    background-color: aliceblue;"
			>
		 		<div class="col-md-12">
					<label>Remarks</label>	 
					<input id="remarks" name="remarks" type="text" class="form-control input-sm text-uppercase" readonly>
				</div>

			 	<div class="col-md-3">
					<label>Pay To (In Invoice)</label>	 
					<input id="suppcode" name="suppcode" type="text" class="form-control input-sm text-uppercase" readonly>
				</div>
			 	<div class="col-md-5">
					<label>&nbsp;</label>	 
					<input id="name" name="name" type="text" class="form-control input-sm text-uppercase" readonly>
			 	</div>
			 	<div class="col-md-4" style="height: 60px;">
					<label>&nbsp;</label>
				</div>
				
			 	<div class="col-md-3">
					<label>Pay To</label>	 
					<input id="payto" name="payto" type="text" class="form-control input-sm text-uppercase" readonly>
				</div>
			 	<div class="col-md-5">
					<label>&nbsp;</label>	 
					<input id="payto_desc" name="payto_desc" type="text" class="form-control input-sm text-uppercase" readonly>
			 	</div>
			 	<div class="col-md-2">
					<label>Amount</label>	 
					<input id="amount" name="amount" type="text" class="form-control input-sm text-uppercase" readonly>
			 	</div>
			 	<div class="col-md-2">
					<label>Outstanding Amount</label>	 
					<input id="outamount" name="outamount" type="text" class="form-control input-sm text-uppercase" readonly>
			 	</div>

		 	</div>

		 	<div class="col-md-4">
					<label>Post Date</label>	 
					<input id="postdate" name="postdate" type="date" class="form-control input-sm text-uppercase" data-validation="required">
		 	</div>
		 	<div class="col-md-12">
				<label>Reasons</label>	 
				<input id="reason" name="reason" type="text" class="form-control input-sm text-uppercase" data-validation="required">
			</div>
		</form>
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
	<script src="js/finance/CM/trtobank/trtobank.js"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>

	
@endsection