@extends('layouts.main')

@section('title', 'Transaction Type')

@section('body')

	@include('layouts.default_search_and_table')
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">
				
				<div class="form-group">
                	<label class="col-md-2 control-label" for="trantype">Transaction Type</label>  
                      <div class="col-md-3">
                      <input id="trantype" name="trantype" type="text" maxlength="10" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-8">
                      <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="isstype">Issue Type</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="isstype" value='Issue' checked>Issue</label>
					<label class="radio-inline"><input type="radio" name="isstype" value='Transfer'>Transfer</label>
					<label class="radio-inline"><input type="radio" name="isstype" value='Adjustment'>Adjustment</label>
					<label class="radio-inline"><input type="radio" name="isstype" value='Others'>Others</label>
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
					<label class="radio-inline"><input type="radio" name="crdbfl" value='In' checked>In</label>
					<label class="radio-inline"><input type="radio" name="crdbfl" value='Out'>Out</label>
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
                             
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='Adjustment' checked>Adjustment</label></td>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='Stock'>Stock</label></td>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='Accrual'>Accrual</label></td>
								</tr>
							
				 			<tr>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='Expense'>Expense</label></td>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='Loan'>Loan</label></td>
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='Cost Of Sale'>Cost Of Sale</label></td>
							</tr>
                            
                            <tr>
				 			
                                <td><label class="radio-inline"><input type="radio" name="accttype" value='Write Off'>Write Off</label></td>
                               <td> <label class="radio-inline"><input type="radio" name="accttype" value='Others'>Others</label></td>
                               
                               </tr>
                               </table>				
                </div>
				</div>

                <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-3">
					<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
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
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						<div class="col-md-3">
						  	<input id="ipaddress" name="ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>    
            </form>
		</div>
	@endsection

@section('scripts')

	<script src="js/material/Transaction Type/tranTypeScript.js"></script>

@endsection