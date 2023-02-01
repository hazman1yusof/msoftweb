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
		<!-- margin-right: 5px; -->
	}

	.collapsed ~ .panel-body {
		padding: 0;
	}

	.clearfix {
		overflow: auto;
	}
	
	<!-- start RC -->
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
	<!-- end RC -->
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

		<div class="panel panel-default" style="position: relative;" id="jqGridDtl_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGridDtl_panel">
				<!-- <b>Credit No: </b><span id="CreditNo_show"></span><br>  -->
				<!-- <b>CUSTOMER NAME: </b><span id="CustName_show"></span> -->
				
				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Detail</h5>
				</div>
			</div>

			<div id="jqGridDtl_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<!-- @if (strtoupper(Request::get('scope')) == 'CANCEL')
						<button 
								type="button" 
								class="btn btn-danger btn-sm" 
								id="but_post2_jq"
								data-oper="cancel"
								style="float: right;margin: 0px 20px 10px 20px;">
								Cancel Credit Note
						</button>
					@endif -->
					<div id="" class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3" class="table table-striped"></table>
						<div id="jqGridPagerDtl"></div>
					</div>
				</div>
			</div>	
		</div>

		<div class="panel panel-default" style="position: relative;" id="jqGridAlloc_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridAlloc_panel">
				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Allocation</h5>
				</div>
			</div>
			<div id="jqGridAlloc_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGridAlloc" class="table table-striped"></table>
						<div id="jqGridPagerAlloc"></div>
					</div>
				</div>
			</div>	
		</div>
		<!-- </div> -->
    </div>
	<!-- ***************End Search + table ********************* -->

	<!-- *************** View Form for Credit ********************* -->
	<div id="dialogForm_CN" title="Viewing Detail" >
		<div class='panel panel-info'>
			<div class="panel-heading"> Credit Header </div>
			<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
				<form class='form-horizontal' style='width:99%' id='formdata_CN'>
					{{ csrf_field() }}
					<input id="db_idno" name="db_idno" type="hidden">
					<input id="db_source" name="db_source" type="hidden">
					<input id="db_trantype" name="db_trantype" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_trantype2">Transaction Type</label> 
						<div class="col-md-2">
							<select id="db_trantype2" name=db_trantype2 class="form-control" data-validation="required">
								<option value = "CN">Credit Note</option>
								<option value = "CNU">Credit Note Unallocated</option>
							</select>
						</div>

						<label class="col-md-3 control-label" for="db_debtorcode">Debtor</label>	 
						<div class="col-md-2">
							<div class='input-group'>
								<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="posteddate">Posted Date</label>  
						<div class="col-md-2">
							<input id="posteddate" name="posteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
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

						<label class="col-md-3 control-label" for="db_recstatus">Record Status</label>  
						<div class="col-md-2">
							<input id="db_recstatus" name="db_recstatus" maxlength="10" class="form-control input-sm" rdonly>
						</div>
					</div>

					<hr>
	
					<div class="form-group">
						<label class="col-md-2 control-label" for="db_reference">Reference No.</label>  
						<div class="col-md-2">
							<input id="db_reference" name="db_reference" class="form-control input-sm text-uppercase">
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
							<textarea class="form-control input-sm text-uppercase" name="db_remark" rows="5" cols="55" maxlength="400" id="db_remark" ></textarea>
						</div>
					</div>

					<hr/>
				</form>
			</div>
		</div>
			
		<div class='panel panel-info' id="grid_dtl">
			<div class="panel-heading">Credit Note Detail</div>
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
			
		<div class='panel panel-info' id="grid_alloc">
			<div class="panel-heading">Allocation</div>
			<div class="panel-body">
				<form id='formdata2_Alloc' class='form-vertical' style='width:99%'>
					<input type="hidden" id="jqGrid2Alloc_itemcode_refresh" name="" value="0">

					<div id="jqGrid2_Alloc_c" class='col-md-12'>
						<table id="jqGrid2_Alloc" class="table table-striped"></table>
						<div id="jqGridPager2_Alloc"></div>
					</div>
				</form>
			</div>

			<div class="panel-body">
				<div class="noti" style="color:red"></div>
			</div>
		</div>
		<!-- </div> -->
	</div>
	<!-- ***************End View Form for Credit ********************* -->

	<!-- *************** View Form for Debit ********************* -->
	<div id="dialogForm_DN" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Debit Header</div>
			<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
				<form class='form-horizontal' style='width:99%' id='formdata_DN'>
					{{ csrf_field() }}
					<input id="db_idno" name="db_idno" type="hidden">

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_debtorcode">Debtor</label>	 
						<div class="col-md-3">
							<div class='input-group'>
								<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-2 control-label" for="posteddate">Posted Date</label>  
						<div class="col-md-2">
							<input id="posteddate" name="posteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_auditno">Debit No</label>  
						<div class="col-md-2"> 
							<input id="db_auditno" name="db_auditno" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" rdonly>
						</div>

						<label class="col-md-3 control-label" for="db_amount">Total Amount</label>
						<div class="col-md-2">
							<input id="db_amount" name="db_amount" type="text" maxlength="11" class="form-control input-sm" value="0.00" rdonly>
						</div>
					</div>
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="db_paymode">Pay Mode</label>	 
						<div class="col-md-2">
							<div class='input-group'>
								<input id="db_paymode" name="db_paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
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
							<input id="db_approveddate" name="db_approveddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" min="<?php $backday= 20; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" 
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
		<!-- </div> -->
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
								<input id="db_deptcode" name="db_deptcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
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
							<input id="db_entrydate" name="db_entrydate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="db_debtorcode">Customer</label>	 
						<div class="col-md-4">
							<div class='input-group'>
								<input id="db_debtorcode" name="db_debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-1 control-label" for="db_hdrtype">Bill Type</label>  
						<div class="col-md-2"> 
							<div class='input-group'>
								<input id="db_hdrtype" name="db_hdrtype" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
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
							<select class="form-control col-md-3" id='db_termmode' name='db_termmode' data-validation="required" data-validation-error-msg="Please Enter Value">
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
							<input id="posteddate" name="posteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
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
		<!-- </div> -->
	</div>
	<!-- ***************End View Form for Sales Order ********************* -->

	<!-- *************** View Form for Receipt ********************* -->
	<div id="dialogForm_RC" title="Add Form" >
		<form style='width:99%' id='formdata_RC' autocomplete="off">
			{{ csrf_field() }}
			<input type='hidden' name='dbacthdr_source' value='PB'>
			<input type='hidden' name='dbacthdr_tillno' >
			<input type='hidden' name='dbacthdr_tillcode' >
			<input type='hidden' name='dbacthdr_hdrtype'>
			<input type='hidden' name='dbacthdr_paytype' id='dbacthdr_paytype'>
			<input type='hidden' name='dbacthdr_auditno'>
			<input type='hidden' name='updpayername'> 
			<input type='hidden' name='updepisode'>
			<input type='hidden' name='dbacthdr_lineno_' value='1'> 
			<input type='hidden' name='dbacthdr_epistype'>
			<input type='hidden' name='dbacthdr_billdebtor'>
			<input type='hidden' name='dbacthdr_debtorcode'>
			<input type='hidden' name='dbacthdr_lastrcnumber'>
			<input type='hidden' name='dbacthdr_drcostcode'>
			<input type='hidden' name='dbacthdr_crcostcode'>
			<input type='hidden' name='dbacthdr_dracc'>
			<input type='hidden' name='dbacthdr_cracc'>
			<input type='hidden' name='dbacthdr_idno'>
			<input type='hidden' name='dbacthdr_currency' value='RM'>
			<input type='hidden' name='postdate'>
			<input type='hidden' name='dbacthdr_RCOSbalance'>
			<input type='hidden' name='dbacthdr_units'>

			<div class='col-md-6'>
				<div class='panel panel-info'>
					<div class="panel-heading">Select either Receipt or Deposit</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="radio-inline"><input type="radio" name="optradio" value='receipt' checked>Receipt</label>
							<label class="radio-inline"><input type="radio" name="optradio" value='deposit'>Deposit</label>
						</div>
						<div id="sysparam_c" class="form-group">
							<table id="sysparam" class="table table-striped"></table>
							<div id="sysparampg"></div>
						</div>
						<hr>
						<div class="form-group">
							<div class='col-md-2 minuspad-15'>
								<label>Trantype: </label><input id="dbacthdr_trantype" name="dbacthdr_trantype" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
							</div>

							<div class='col-md-10 '>
								<label>Description: </label><input id="dbacthdr_PymtDescription" name="dbacthdr_PymtDescription" type="text" class="form-control input-sm" rdonly>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class='col-md-6'>
				<div class='panel panel-info'>
					<div class="panel-heading">Choose Payer Code</div>
					<div class="panel-body">
						<div class="col-md-12 minuspad-15">
							<label class="control-label" for="dbacthdr_payercode">Payer Code</label>  
							<div class='input-group'>
								<input id="dbacthdr_payercode" name="dbacthdr_payercode" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value"/>
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
						</div>

						<div class="col-md-12 minuspad-15">
							<label class="control-label" for="dbacthdr_payername">Payer Name</label>
							<div class=''>
								<input id="dbacthdr_payername" name="dbacthdr_payername" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
							</div>
						</div>

						<div class="col-md-6 minuspad-15">
							<label class="control-label" for="dbacthdr_debtortype">Financial Class</label>
							<div class=''>
								<input id="dbacthdr_debtortype" name="dbacthdr_debtortype" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
							</div>
							<span class="help-block"></span>
						</div>

						<div class='clearfix'></div>
						<hr>

						<label class="control-label" for="dbacthdr_debtortype">Receipt Number</label>
						<input id="dbacthdr_recptno" name="dbacthdr_recptno" type="text" class="form-control input-sm text-uppercase" rdonly>

						<div id='divMrnEpisode'>
							<div class="col-md-8 minuspad-15">
								<label class="control-label" for="dbacthdr_mrn">MRN</label>  
								<div class="">
									<div class='input-group'>
										<input id="dbacthdr_mrn" name="dbacthdr_mrn" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>
							</div>

							<div class="col-md-4 minuspad-15">
								<label class="control-label" for="dbacthdr_episode">Episode</label>  
								<div class="">
									<div class=''>
										<input id="dbacthdr_episno" name="dbacthdr_episno" type="text" class="form-control input-sm" rdonly>
									</div>
									<span class="help-block"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class='col-md-12'>
				<div class="form-group">
					<label class="control-label col-md-1" for="dbacthdr_remark">Remark</label> 
					<div class='col-md-11'> 
						<input id="dbacthdr_remark" name="dbacthdr_remark" type="text" class="form-control input-sm text-uppercase">
					</div>
				</div>
				<div class='clearfix'></div>
				<hr>
			</div>
		</form>
		<div class='col-md-12'>
			<div class='panel panel-info'>
				<div class="panel-heading">Choose type of exchange</div>
				<div class="panel-body">
					<ul class="nav nav-tabs">
						<li><a data-toggle="tab" href="#tab-cash" form='#f_tab-cash'>Cash</a></li>
						<li><a data-toggle="tab" href="#tab-card" form='#f_tab-card'>Card</a></li>
						<li><a data-toggle="tab" href="#tab-cheque" form='#f_tab-cheque'>Cheque</a></li>
						<li><a data-toggle="tab" href="#tab-debit" form='#f_tab-debit'>Auto Debit</a></li>
						<li><a data-toggle="tab" href="#tab-forex" form='#f_tab-forex'>Forex</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab-cash" class="tab-pane fade form-horizontal">
							<form id='f_tab-cash' autocomplete="off">
								<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
								<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CASH">
								</br>
								<div class="myformgroup">
									<label class="control-label col-md-2" for="dbacthdr_amount">Payment</label> 
									<div class='col-md-4'> 
										<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
									</div>

									<label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label> 
									<div class='col-md-4'> 
										<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2" for="dbacthdr_RCCASHbalance">Cash Balance</label> 
									<div class='col-md-4'> 
										<input id="dbacthdr_RCCASHbalance" name="dbacthdr_RCCASHbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
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
									<div class="form-group">
										<div class='col-md-4'> 
											<label class="control-label" for="dbacthdr_amount">Payment</label> 
											<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
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
											<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" rdonly>
										</div>
									</div>
									<div class="form-group">
										<div class='col-md-6'>
											<label class="control-label" for="dbacthdr_authno">Authorization No.</label> 
											<div class=''> 
												<input id="dbacthdr_authno" name="dbacthdr_authno" type="text" class="form-control input-sm text-uppercase" rdonly>
											</div>
										</div>
										<div class='col-md-6'>
											<label class="control-label" for="dbacthdr_expdate">Expiry Date</label> 
											<div class=''> 
												<input id="dbacthdr_expdate" name="dbacthdr_expdate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" rdonly>
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
										<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" rdonly>
									</div>

									<label class="control-label col-md-2" for="dbacthdr_amount">Payment</label> 
									<div class='col-md-4'> 
										<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
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
										<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
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
										<div class='col-md-4'> 
											<label class="control-label" for="dbacthdr_entrydate">Transaction Date</label> 
											<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" rdonly>

										</div>
									</div>
									<div class="clearfix"></div>
									<div class="myformgroup">
										<div class='col-md-6'> 
											<label class="control-label" for="dbacthdr_bankcharges">Bank Charges</label> 
											<input id="dbacthdr_bankcharges" name="dbacthdr_bankcharges" type="text" class="form-control input-sm" value="0.00" rdonly>
										</div>
										<div class='col-md-6'> 
											<label class="control-label" for="dbacthdr_amount">Payment</label> 
											<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
										</div>
									</div>
									<div class="form-group">
										<div class='col-md-6'>
											<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
											<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
										</div>
										<div class='col-md-6'>
											<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
											<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
										</div>
									</div>
									<div class="form-group">
										<div class='col-md-12'> 
											<label class="control-label" for="dbacthdr_reference">Reference</label> 
											<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
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
											<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
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
											<input id="dbacthdr_amount2" name="dbacthdr_amount2" type="text" class="form-control input-sm" value="0.00" rdonly>
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
	<!-- ***************End View Form for Receipt ********************* -->

	<!-- *************** View Form for Refund ********************* -->
	<!-- <div id="dialogForm_RF" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Refund Header</div>
			<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
				<form class='form-horizontal' style='width:99%' id='formdata_RF'>
					{{ csrf_field() }}
					<input type='hidden' name='dbacthdr_source' value='PB'>
					<input type='hidden' name='dbacthdr_tillno' >
					<input type='hidden' name='dbacthdr_tillcode' >
					<input type='hidden' name='dbacthdr_hdrtype'>
					<input type='hidden' name='dbacthdr_paytype' id='dbacthdr_paytype'>
					<input type='hidden' name='dbacthdr_auditno'>
					<input type='hidden' name='updpayername'> 
					<input type='hidden' name='updepisode'>
					<input type='hidden' name='dbacthdr_lineno_' value='1'> 
					<input type='hidden' name='dbacthdr_epistype'>
					<input type='hidden' name='dbacthdr_billdebtor'>
					<input type='hidden' name='dbacthdr_debtorcode'>
					<input type='hidden' name='dbacthdr_debtortype'>
					<input type='hidden' name='dbacthdr_payername'>
					<input type='hidden' name='dbacthdr_lastrcnumber'>
					<input type='hidden' name='dbacthdr_drcostcode'>
					<input type='hidden' name='dbacthdr_crcostcode'>
					<input type='hidden' name='dbacthdr_dracc'>
					<input type='hidden' name='dbacthdr_cracc'>
					<input type='hidden' name='dbacthdr_idno'>
					<input type='hidden' name='dbacthdr_currency' value='RM'>
					<input type='hidden' name='postdate'>
					<input type='hidden' name='dbacthdr_RCOSbalance'>
					<input type='hidden' name='dbacthdr_units'>
        
					<div class='col-md-12' style="padding:15px 5px">
						<div class="form-group col-md-12">
							<label class="control-label col-md-1" for="dbacthdr_recptno">Refund No</label>
							<div class="col-md-2">
								<input id="dbacthdr_recptno" name="dbacthdr_recptno" type="text" class="form-control input-sm text-uppercase" rdonly>
							</div>

							<label class="control-label col-md-1" for="dbacthdr_payercode">Payer</label>
							<div class="col-md-4">
								<div class='input-group'>
									<input id="dbacthdr_payercode" name="dbacthdr_payercode" type="text" class="form-control input-sm text-uppercase">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group col-md-12">
							<label class="control-label col-md-1" for="dbacthdr_remark">Remark</label>
							<div class="col-md-8"> 
								<textarea class="form-control input-sm text-uppercase" name="dbacthdr_remark" rows="3" cols="55" id="dbacthdr_remark"> </textarea>
							</div>
						</div>
					</div>

					<div class='col-md-12'>
						<div class='panel panel-info'>
							<div class="panel-heading">Choose type of exchange</div>
							<div class="panel-body">
								<ul class="nav nav-tabs">
									<li><a data-toggle="tab" href="#tab-cash" form='#f_tab-cash'>Cash</a></li>
									<li><a data-toggle="tab" href="#tab-card" form='#f_tab-card'>Card</a></li>
									<li><a data-toggle="tab" href="#tab-cheque" form='#f_tab-cheque'>Cheque</a></li>
									<li><a data-toggle="tab" href="#tab-debit" form='#f_tab-debit'>Auto Debit</a></li>
								</ul>

								<div class="tab-content">
									<div id="tab-cash" class="tab-pane fade form-horizontal">
										<form id='f_tab-cash' autocomplete="off">
											<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
											<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CASH">
											</br>
											<div class="myformgroup">
												<label class="control-label col-md-2" for="dbacthdr_amount">Refund Amount</label> 
												<div class='col-md-3'> 
													<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
												</div>

												<label class="control-label col-md-2" for="dbacthdr_allocamt">Allocation Amount</label> 
												<div class='col-md-3'> 
													<input id="dbacthdr_allocamt" name="dbacthdr_allocamt" type="text" class="form-control input-sm" value="0.00" rdonly>
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
												<div class="form-group">
													<div class='col-md-4'> 
														<label class="control-label" for="dbacthdr_amount">Refund Amount</label> 
														<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
													</div>

													<div class='col-md-4'> 
														<label class="control-label" for="dbacthdr_allocamt">Allocation Amount</label> 
														<input id="dbacthdr_allocamt" name="dbacthdr_allocamt" type="text" class="form-control input-sm" value="0.00" rdonly>
													</div>
												</div>
												<div class="clearfix"></div>
												<div class="form-group">
													<div class='col-md-12'>
														<label class="control-label" for="dbacthdr_reference">Reference</label> 
														<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase">
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
															<input id="dbacthdr_expdate" name="dbacthdr_expdate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
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
											</div>
											<div class="myformgroup">
												<label class="control-label col-md-2" for="dbacthdr_amount">Refund Amount</label> 
												<div class='col-md-3'> 
													<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
												</div>
												<label class="control-label col-md-2" for="dbacthdr_allocamt">Allocation Amount</label> 
												<div class='col-md-3'> 
													<input id="dbacthdr_allocamt" name="dbacthdr_allocamt" type="text" class="form-control input-sm" value="0.00" rdonly>
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
													<div class='col-md-4'> 
														<label class="control-label" for="dbacthdr_entrydate">Transaction Date</label> 
														<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">

													</div>
												</div>
												<div class="clearfix"></div>
												<div class="myformgroup">
													<div class='col-md-6'> 
														<label class="control-label" for="dbacthdr_bankcharges">Bank Charges</label> 
														<input id="dbacthdr_bankcharges" name="dbacthdr_bankcharges" type="text" class="form-control input-sm" value="0.00">
													</div>
													<div class='col-md-6'> 
														<label class="control-label" for="dbacthdr_amount">Payment</label> 
														<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
													</div>
												</div>

												<div class="myformgroup">
													<div class='col-md-6'> 
														<label class="control-label" for="dbacthdr_allocamt">Allocation Amount</label> 
														<input id="dbacthdr_allocamt" name="dbacthdr_allocamt" type="text" class="form-control input-sm" value="0.00" rdonly>
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
								</div>
							</div>
						</div>
					</div>

					<div class='col-md-12' id='gridAllo_c' style="padding:0">
						<hr>
						<table id="gridAllo" class="table table-striped"></table>
						<div id="pagerAllo"></div>
					</div>

					<div class="col-md-10 col-md-offset-1" id="alloSearch">
						<label class="control-label" id='alloLabel'>Search</label>
						<input id="alloText" type="text" class="form-control input-sm">
						<select class="form-control" id="alloCol">
							<option value="invno" >invoice no</option>
							<option value="auditno" >auditno</option>
							<option value="mrn" >mrn</option>
							<option value="recptno" >docno</option>
							<option value="newic" >newic</option>
							<option value="staffid" >staffid</option>
							<option value="batchno" >batchno</option>
						</select>
					</div>
				</form>
			</div>
		</div>
	</div> -->
	<!-- ***************End View Form for Refund ********************* -->

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