@extends('layouts.main')

@section('title', 'Transaction Type')

@section('body')

	<!-------------------------------- Search + table ---------------------->
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
		<div class="panel-heading">Transaction Type Header</div>
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
				</div>
			</div>
		</div>
	</div>

	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">
				
				<div class="form-group">
                	<label class="col-md-2 control-label" for="trantype">Transaction Type</label>  
                      <div class="col-md-3">
                      <input id="trantype" name="trantype" type="text" maxlength="10" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-8">
                      <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required">
                      </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="isstype">Issue Type</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="isstype" value='ISSUE' checked>Issue</label>
					<label class="radio-inline"><input type="radio" name="isstype" value='TRANSFER'>Transfer</label>
					<label class="radio-inline"><input type="radio" name="isstype" value='ADJUSTMENT'>Adjustment</label>
					<label class="radio-inline"><input type="radio" name="isstype" value='LOAN'>Loan</label>
					<label class="radio-inline"><input type="radio" name="isstype" value='WRITE-OFF'>Write Off</label>
					<label class="radio-inline"><input type="radio" name="isstype" value='NONE'>None</label>
				  </div>
				
				  <label class="col-md-2 control-label" for="trbyiv">Transaction By Inventory</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="trbyiv" value='1' checked>Yes</label>
					<label class="radio-inline"><input type="radio" name="trbyiv" value='0'>No</label>
				  </div>
				</div> 

				<div class="form-group">
				  <label class="col-md-2 control-label" for="updqty">Update Quantity</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="updqty" value='1' checked>Yes</label>
					<label class="radio-inline"><input type="radio" name="updqty" value='0'>No</label>
				  </div>
				
				  <label class="col-md-2 control-label" for="crdbfl">Credit/Debit</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="crdbfl" value='IN' checked>In</label>
					<label class="radio-inline"><input type="radio" name="crdbfl" value='OUT'>Out</label>
				  </div>
				</div> 

				<div class="form-group">
				  <label class="col-md-2 control-label" for="updamt">Update GL</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="updamt" value='1' checked>Yes</label>
					<label class="radio-inline"><input type="radio" name="updamt" value='0'>No</label>
				  </div>
			
				  <label class="col-md-2 control-label" for="accttype">Account Type</label>  
				  <div class="col-md-3">
				    <table>
                             	<tr>
                             
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='ADJUSTMENT' checked>Adjustment</label></td>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='STOCK'>Stock</label></td>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='ACCRUAL'>Accrual</label></td>
								</tr>
							
				 			<tr>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='EXPENSE'>Expense</label></td>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='LOAN'>Loan</label></td>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='COST OF SALE'>Cost Of Sale</label></td>
							</tr>
                            
                            <tr>
				 			
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='WRITE OFF'>Write Off</label></td>
                               <td> <label class="radio-inline">
                               	<input type="radio" name="accttype" value='OTHERS'>Others</label>
                               </td>
                               <td> 
	                               	<label class="radio-inline">
		                               	<input type="radio" name="accttype" value='NONE'>None
		                               </label>
                               </td>
                               
                               </tr>
                               </table>				
                </div>
				</div>

                <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
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
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm"  frozeOnEdit hideOne>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						<div class="col-md-3">
						  	<input id="ipaddress" name="ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm"  frozeOnEdit hideOne>
						  	</div>
				</div>    
            </form>
		</div>
	@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
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
	<script src="js/material/Transaction Type/tranTypeScript.js"></script>

@endsection