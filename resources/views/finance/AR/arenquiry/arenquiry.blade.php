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

		<!-- *************** View Form for Credit/Debit ********************* -->
	<div id="dialogForm_cn" title="Viewing Detail" >
		<div class='panel panel-info'>
			<div class="panel-heading"> Header Detail</div>
				<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
					<form class='form-horizontal' style='width:99%' id='formdata_cn'>
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
						<form id='formdata2_cn' class='form-vertical' style='width:99%'>
							<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

							<div id="jqGrid2_cn_c" class='col-md-12'>
								<table id="jqGrid2_cn" class="table table-striped"></table>
					            <div id="jqGridPager2_cn"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti" style="color:red"></div>
					</div>
			</div>
				
			<!-- <div id="dialog_remarks" title="Remarks">
			  	<div class="panel panel-default">
			    	<div class="panel-body">
			    		<textarea id='remarks2' name='remarks2' rows='6' class="form-control input-sm text-uppercase" style="width:100%;"></textarea>
			    	</div>
			  	</div>
			</div> -->
		</div>
	</div>
		<!-- ***************End View Form for Credit/Debit ********************* -->

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