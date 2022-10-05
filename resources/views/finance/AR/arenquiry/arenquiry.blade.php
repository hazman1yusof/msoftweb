@extends('layouts.main')

@section('title', 'AR Enquiry')

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
							<input style="display:none" name="Stext" type="search" placeholder="Search Here ..." class="form-control text-uppercase" tabindex="2">

							<div id="customer_text">
								<div class='input-group'>
									<input id="customer_search" name="customer_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="customer_search_hb" class="help-block"></span>
							</div>

							<div id="department_text" style="display:none">
								<div class='input-group'>
									<input id="department_search" name="department_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="department_search_hb" class="help-block"></span>
							</div>

							<div id="docuDate_text" class="form-inline" style="display:none">
								FROM DATE <input id="docuDate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
								TO DATE <input id="docuDate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
								<button type="button" class="btn btn-primary btn-sm" id="docuDate_search">SEARCH</button>
							</div>
							
						</div>
		            </div>
				</div>
			</fieldset> 
		</form>
		 
		<div class="panel panel-default">
		    <div class="panel-heading">Enquiry (AR) Header</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        			</div>
		    	</div>
			</div>
		</div>
    </div>
	<!-- ***************End Search + table ********************* -->

		<!-- *************** View Form for Credit ********************* -->
	<div id="dialogForm_CN" title="Viewing Detail" >
		<div class='panel panel-info'>
			<div class="panel-heading"> Header Detail</div>
				<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
					<form class='form-horizontal' style='width:99%' id='formdata_CN'>
						{{ csrf_field() }}
						<input id="db_idno" name="db_idno" type="hidden">
						<input id="db_source" name="db_source" type="hidden">
						<input id="db_trantype" name="db_trantype" type="hidden">


						<div class="form-group">
							<label class="col-md-2 control-label" for="db_debtorcode">Debtor</label>	 
							<div class="col-md-3">
								<div class='input-group'>
								<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<label class="col-md-2 control-label" for="posteddate">Posted Date</label>  
								<div class="col-md-2">
								<input id="posteddate" name="posteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_auditno">Credit/Debit No</label>  
							<div class="col-md-2"> 
								<input id="db_auditno" name="db_auditno" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" rdonly>
							</div>
							
							<label class="col-md-3 control-label" for="db_entrydate">Document Date</label>  
							<div class="col-md-2">
								<input id="db_entrydate" name="db_entrydate" type="date" maxlength="10" class="form-control input-sm"   value="<?php echo date("Y-m-d"); ?>" min="<?php $backday= 20; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" 
									max="<?php echo date('Y-m-d');?>">
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-md-2 control-label" for="db_amount">Total Amount</label>
							<div class="col-md-2">
								<input id="db_amount" name="db_amount" type="text" maxlength="11" class="form-control input-sm" value="0.00" rdonly>
							</div>

							<label class="col-md-3 control-label" for="db_recstatus">Record Status</label>  
							<div class="col-md-2">
									<input id="db_recstatus" name="db_recstatus" maxlength="10" class="form-control input-sm" rdonly>
							</div>
						</div>

						<hr>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_approveddate">Approved Date</label>  
							<div class="col-md-2"> 
							<input id="db_approveddate" name="db_approveddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>" min="<?php $backday= 20; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" 
									max="<?php echo date('Y-m-d');?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_remark">Remarks</label> 
							<div class="col-md-7"> 
							<textarea class="form-control input-sm text-uppercase" name="db_remark" rows="5" cols="55" maxlength="400" id="db_remark" ></textarea>
							</div>
						</div>

					<hr/>

					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading"> Detail </div>
					<div class="panel-body">
						<form id='formdata2_CN' class='form-vertical' style='width:99%'>
							<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

							<div id="jqGrid2_CN_c" class='col-md-12'>
								<table id="jqGrid2_CN" class="table table-striped"></table>
					            <div id="jqGridPager2_CN"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti" style="color:red"></div>
					</div>
			</div>
		</div>
	</div>
		<!-- ***************End View Form for Credit ********************* -->

		<!-- *************** View Form for Debit ********************* -->
		<div id="dialogForm_DN" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Debit Note Header</div>
				<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
					<form class='form-horizontal' style='width:99%' id='formdata_DN'>
						{{ csrf_field() }}
						<input id="db_idno" name="db_idno" type="hidden">


						<div class="form-group">
							<label class="col-md-2 control-label" for="db_debtorcode">Debtor</label>	 
							<div class="col-md-3">
								<div class='input-group'>
								<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<label class="col-md-2 control-label" for="posteddate">Posted Date</label>  
								<div class="col-md-2">
								<input id="posteddate" name="posteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_auditno">Debit No</label>  
							<div class="col-md-2"> 
								<input id="db_auditno" name="db_auditno" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" rdonly>
							</div>
							
							<label class="col-md-3 control-label" for="db_entrydate">Document Date</label>  
							<div class="col-md-2">
								<input id="db_entrydate" name="db_entrydate" type="date" maxlength="10" class="form-control input-sm"   value="<?php echo date("Y-m-d"); ?>" min="<?php $backday= 20; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" 
									max="<?php echo date('Y-m-d');?>">
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-md-2 control-label" for="db_amount">Total Amount</label>
							<div class="col-md-2">
								<input id="db_amount" name="db_amount" type="text" maxlength="11" class="form-control input-sm" value="0.00" rdonly>
							</div>

							<label class="col-md-3 control-label" for="db_recstatus">Record Status</label>  
							<div class="col-md-2">
									<input id="db_recstatus" name="db_recstatus" maxlength="10" class="form-control input-sm" rdonly>
							</div>
						</div>

						<hr>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_approveddate">Approved Date</label>  
							<div class="col-md-2"> 
							<input id="db_approveddate" name="db_approveddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>" min="<?php $backday= 20; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" 
									max="<?php echo date('Y-m-d');?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_remark">Remarks</label> 
							<div class="col-md-7"> 
							<textarea class="form-control input-sm text-uppercase" name="db_remark" rows="5" cols="55" maxlength="400" id="db_remark" ></textarea>
							</div>
						</div>

					<hr/>

					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Debit Note Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

							<div id="jqGrid2_DN_c" class='col-md-12'>
								<table id="jqGrid2_DN" class="table table-striped"></table>
					            <div id="jqGridPager2_DN"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti" style="color:red"></div>
					</div>
			</div>
				
		</div>
	</div>
		<!-- ***************End View Form for Debit ********************* -->

		<!-- *************** View Form for Sales Order ********************* -->
		<div id="dialogForm_IN" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Sales Order Header</div>
				<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
					<form class='form-horizontal' style='width:99%' id='formdata_IN'>
						{{ csrf_field() }}
						<input id="db_idno" name="db_idno" type="hidden">
						<input id="db_source" name="db_source" type="hidden">
						<input id="db_trantype" name="db_trantype" type="hidden">
						<input id="pricebilltype" name="pricebilltype" type="hidden">


						<div class="form-group">
							<label class="col-md-2 control-label" for="db_deptcode">Store Dept</label>	 
							<div class="col-md-4">
								<div class='input-group'>
								<input id="db_deptcode" name="db_deptcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
									<span class="help-block"></span>
								</div>

							<label class="col-md-1 control-label" for="db_invno">Invoice No</label>  
							<div class="col-md-2">
								<input id="db_invno" name="db_invno" type="text" class="form-control input-sm" rdonly>
							</div>

							<label class="col-md-1 control-label" for="db_entrydate">Document Date</label>  
							<div class="col-md-2">
								<input id="db_entrydate" name="db_entrydate" type="date" maxlength="12" class="form-control input-sm" data-validation="required"  value="{{Carbon\Carbon::now()->format('Y-m-d')}}" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_debtorcode">Customer</label>	 
							<div class="col-md-4">
								<div class='input-group'>
								<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<label class="col-md-1 control-label" for="db_hdrtype">Bill Type</label>  
							<div class="col-md-2"> 
								<div class='input-group'>
									<input id="db_hdrtype" name="db_hdrtype" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" >
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>							
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_termdays">Term</label>  
							<div class="col-md-1">
								<input id="db_termdays" name="db_termdays" type="text" value ="30" class="form-control input-sm">
							</div>

							<div class="col-md-2">
								<select class="form-control col-md-3" id='db_termmode' name='db_termmode' data-validation="required">
									<option value='DAYS' selected>DAYS</option>
									<option value='MONTH'>MONTH</option>
									<option value='YEAR'>YEAR</option>
								</select> 
							</div>

							<label class="col-md-2 control-label" for="db_mrn">MRN</label>  
							<div class="col-md-2"> 
								<div class='input-group'>
									<input id="db_mrn" name="db_mrn" type="text" maxlength="12" class="form-control input-sm text-uppercase" >
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>	
						</div>

						<div class="form-group">		
							<label class="col-md-2 control-label" for="db_orderno">Order No</label>  
							<div class="col-md-2"> 
								<input id="db_orderno" name="db_orderno" type="text" class="form-control input-sm text-uppercase" >
							</div>
							
							<label class="col-md-3 control-label" for="db_auditno">Auto No</label>  
							<div class="col-md-2"> 
								<input id="db_auditno" name="db_auditno" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" rdonly>
							</div>

							<label class="col-md-1 control-label" for="posteddate">Posted Date</label>  
								<div class="col-md-2">
								<input id="posteddate" name="posteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
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
								<input id="db_podate" name="db_podate" type="date" maxlength="10" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_amount">Total Amount</label>
							<div class="col-md-2">
								<input id="db_amount" name="db_amount" type="text" maxlength="11" class="form-control input-sm" value="0.00" rdonly>
							</div>

							<label class="col-md-3 control-label" for="db_recstatus">Record Status</label>  
							<div class="col-md-2">
									<input id="db_recstatus" name="db_recstatus" maxlength="10" class="form-control input-sm" rdonly>
							</div>
						</div>

						<hr>

						<div class="form-group">
							<label class="col-md-2 control-label" for="db_remark">Remarks</label> 
							<div class="col-md-6"> 
							<textarea class="form-control input-sm text-uppercase" name="db_remark" rows="5" cols="55" maxlength="400" id="db_remark" ></textarea>
							</div>
						</div>

						<div class="form-group data_info">
							<div class="col-md-6 minuspad-13">
									<label class="control-label" for="db_adduser">Last Entered By</label>  
						  			<input id="db_adduser" name="db_adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="db_adddate">Last Entered Date</label>
						  			<input id="db_adddate" name="db_adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="postedby">Authorized By</label>  
						  			<input id="postedby" name="postedby" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="posteddate">Authorized Date</label>
						  			<input id="posteddate" name="posteddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>						    	
							</div>
					<hr/>

					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Sales Order Detail</div>
					<div class="panel-body">
						<form id='formdata2_IN' class='form-vertical' style='width:99%'>
							<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

							<div id="jqGrid2_IN_c" class='col-md-12'>
								<table id="jqGrid2_IN" class="table table-striped"></table>
					            <div id="jqGridPager2_IN"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti" style="color:red"></div>
					</div>
			</div>
		</div>
	</div>
	<!-- ***************End View Form for Sales Order ********************* -->
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
	<script src="js/finance/AR/arenquiry/arenquiryScript.js"></script>

@endsection