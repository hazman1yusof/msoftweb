@extends('layouts.main')

@section('title', 'Debtor Master')

@section('body')
	<!-------------------------------- Search + table -------------------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<div class="ScolClass">
					<div name='Scol'>Search By : </div>
				</div>
				
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="1">
				</div>
			</fieldset>
		</form>
		
		<div class="panel panel-default">
			<div class="panel-heading">Debtor Master Setup Header
				<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #518351;" id='excelgen1' href="./debtorMaster/showExcel" target="_blank">
					<span class='fa fa-file-excel-o fa-lg'></span> Download Excel
				</a>
				<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #a35252;" id='pdfgen1' href="./debtorMaster/showpdf" target="_blank">
					<span class='fa fa-file-pdf-o fa-lg'></span> Print PDF
				</a>
			</div>
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
				</div>
			</div>
		</div>
	</div>
	<!-------------------------------- End Search + table -------------------------------->
	
	<div id="dialogForm" title="Add Form">
		<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
			
			<input id='idno' name='idno' type='hidden'>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="debtortype">Financial Class</label>
				<div class="col-md-3">
					<div class='input-group'>
						<input id="debtortype" name="debtortype" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" frozeOnEdit>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
				
				<label class="col-md-2 control-label" for="debtorcode">Debtor Code</label>
				<div class="col-md-3">
					<input id="debtorcode" name="debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" frozeOnEdit>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="name">Debtor Name</label>
				<div class="col-md-8">
					<input id="name" name="name" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="address1">Address</label>
				<div class="col-md-8">
					<input id="address1" name="address1" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<input id="address2" name="address2" type="text" class="form-control input-sm text-uppercase">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<input id="address3" name="address3" type="text" class="form-control input-sm text-uppercase">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<input id="address4" name="address4" type="text" class="form-control input-sm text-uppercase">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="postcode">PostCode</label>
				<div class="col-md-3">
					<input id="postcode" name="postcode" type="text" class="form-control input-sm">
				</div>
				<label class="col-md-2 control-label" for="billtype">State Code</label>
				<div class="col-md-3">
					<div class='input-group'>
						<input id="statecode" name="statecode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="teloffice">Tel. Office</label>
				<div class="col-md-3">
					<input id="teloffice" name="teloffice" type="text" class="form-control input-sm">
				</div>
				
				<label class="col-md-2 control-label" for="fax">Fax</label>
				<div class="col-md-3">
					<input id="fax" name="fax" type="text" class="form-control input-sm">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="contact">Contact</label>
				<div class="col-md-3">
					<input id="contact" name="contact" type="text" class="form-control input-sm text-uppercase">
				</div>
				
				<label class="col-md-2 control-label" for="position">Position</label>
				<div class="col-md-3">
					<input id="position" name="position" type="text" class="form-control input-sm text-uppercase">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="email">Email</label>
				<div class="col-md-3">
					<input id="email" name="email" type="text" class="form-control input-sm">
				</div>
				
				<label class="col-md-2 control-label" for="accno">Bank Acc. No</label>
				<div class="col-md-3">
					<input id="accno" name="accno" type="text" class="form-control input-sm">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="billtype">Bill Type IP</label>
				<div class="col-md-3">
					<div class='input-group'>
						<input id="billtype" name="billtype" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
				
				<label class="col-md-2 control-label" for="billtypeop">Bill Type OP</label>
				<div class="col-md-3">
					<div class='input-group'>
						<input id="billtypeop" name="billtypeop" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="coverageip">Coverage IP</label>
				<div class="col-md-3">
					<input ids="coverageip" name="coverageip" id="coverageip" type="text" class="form-control input-sm text-uppercase">
				</div>
				
				<label class="col-md-2 control-label" for="coverageop">Coverage OP</label>
				<div class="col-md-3">
					<input id="coverageop" name="coverageop" type="text" class="form-control input-sm text-uppercase">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="recstatus">Record Status</label>
				<div class="col-md-8">
					<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE'>Deactive</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='SUSPEND'>Suspend</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='LEGAL'>Legal</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='DEBT-COLLECTOR'>Debt-Collector</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="creditlimit">Credit Limit</label>
				<div class="col-md-3">
					<input id="creditlimit" name="creditlimit" type="text" class="form-control input-sm">
				</div>
				
				<label class="col-md-2 control-label" for="creditterm">Credit Term</label>
				<div class="col-md-3">
					<input id="creditterm" name="creditterm" type="text" class="form-control input-sm">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="payto">Payable To</label>
				<div class="col-md-3">
					<select class="form-control col-md-4" id='payto' name='payto' data-validation="required" data-validation-error-msg="Please Select Value">
						<option selected></option>
						<option value='UKM SPECIALIST CENTRE'>UKM SPECIALIST CENTRE</option>
						<option value='UKM MEDICARE'>UKM MEDICARE</option>
					</select> 
				</div>
				
				<label class="col-md-2 control-label" for="brnno">BRN No.</label>
				<div class="col-md-3">
					<input id="brnno" name="brnno" type="text" class="form-control input-sm">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="newic">New I/C</label>
				<div class="col-md-3">
					<input id="newic" name="newic" type="text" class="form-control input-sm">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="requestgl">Request GL</label>
				<div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="requestgl" value='1'>Yes</label>
					<label class="radio-inline"><input type="radio" name="requestgl" value='0'>No</label>
				</div>
				<label class="col-md-2 control-label" for="requestgl">Tin ID</label>
				<div class="col-md-3">
					<input id="tinid" name="tinid" type="text" class="form-control input-sm">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="crgroup">Credit Control Group</label>
				<div class="col-md-3">
					<input id="crgroup" name="crgroup" type="text" class="form-control input-sm text-uppercase">
				</div>
				
				<label class="col-md-2 control-label" for="debtorgroup">Debtor Group</label>
				<div class="col-md-3">
					<input id="debtorgroup" name="debtorgroup" type="text" class="form-control input-sm text-uppercase">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="adduser">Created By</label>
				<div class="col-md-3">
					<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
				
				<label class="col-md-2 control-label" for="upduser">Last Entered</label>
				<div class="col-md-3">
					<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="adddate">Created Date</label>
				<div class="col-md-3">
					<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
				
				<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>
				<div class="col-md-3">
					<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="computerid">Computer Id</label>
				<div class="col-md-3">
					<input id="computerid" name="computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
				
				<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>
				<div class="col-md-3">
					<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>
			
			<input id="actdebccode" name="actdebccode" type="hidden" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value">
			
			<input id="actdebglacc" name="actdebglacc" type="hidden" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value">
			
			<input id="depccode" name="depccode" type="hidden" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value">
			
			<input id="depglacc" name="depglacc" type="hidden" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value">
		</form>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function (){
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function (){
					$("table#jqGrid").attr('tabindex', 2);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex', 3);
					$("td#input_jqGridPager input.ui-pg-input.form-control").on('focus',onfocus_pageof);
					if($('table#jqGrid').data('enter')){
						$('td#input_jqGridPager input.ui-pg-input.form-control').focus();
						$("table#jqGrid").data('enter',false);
					}
				});
			}
			
			function onfocus_pageof(){
				$(this).keydown(function (e){
					var code = e.keyCode || e.which;
					if (code == '9'){
						e.preventDefault();
						$('input[name=Stext]').focus();
					}
				});
				
				$(this).keyup(function (e){
					var code = e.keyCode || e.which;
					if (code == '13'){
						$("table#jqGrid").data('enter',true);
					}
				});
			}
		});
	</script>
	<script src="js/finance/AR/debtorMaster/debtorMaster.js?v=1.3"></script>
@endsection