@extends('layouts.main')

@section('title', 'AP Enquiry')

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
                <div class="panel-heading">AP Enquiry Header</div>
                    <div class="panel-body">
                        <div class='col-md-12' style="padding:0 0 15px 0">
                            <table id="jqGrid" class="table table-striped"></table>
                            <div id="jqGridPager"></div>
                        </div>
                    </div>
            </div>
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>

	<div id="dialogForm" title="Viewing Detail" >
		<div class='panel panel-info'>
			<div class="panel-heading">AP Enquiry Header</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
				

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
			<div class="panel-heading">AP Enquiry Detail</div>
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

	<script src="js/finance/AP/apenquiry/apenquiry.js"></script>
	<!-- <script src="plugins/datatables/js/jquery.datatables.min.js"></script> -->
	
@endsection