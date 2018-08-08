@extends('layouts.main')

@section('title', 'Debtor Type')

@section('body')

	@include('layouts.default_search_and_table')
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
			
				{{ csrf_field() }}
				<input type="hidden" name="idno">
				<input id="source2" name="source" type="hidden" value="{{$_GET['source']}}">
			
				<div class="form-group">
				  <label class="col-md-2 control-label" for="paymode">Pay Mode</label>  
				  <div class="col-md-3">
				  <input id="paymode" name="paymode" type="text" maxlength="12" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="paytype">Paytype</label>  
				  <div class="col-md-8">
					    <table>
	                     	<tr>
		                        <td><label class="radio-inline"><input type="radio" name="paytype" value='Bank Draft' checked>Bank Draft</label></td>
		                        <td><label class="radio-inline"><input type="radio" name="paytype" value='Cash'>Cash</label></td>
		                        <td><label class="radio-inline"><input type="radio" name="paytype" value='Cheque'>Cheque</label></td>
							</tr>
								
				 			<tr>
		                        <td><label class="radio-inline"><input type="radio" name="paytype" value='Tele Transfer'>Tele Transfer</label></td>
		                        <td><label class="radio-inline"><input type="radio" name="paytype" value='Bank'>Bank</label></td>
		                        <td><label class="radio-inline"><input type="radio" name="paytype" value='Card'>Card</label></td>
							</tr>
		                            
		                    <tr>
		                        <td><label class="radio-inline"><input type="radio" name="paytype" value='Credit Note'>Credit Note</label></td>
		                    	<td> <label class="radio-inline"><input type="radio" name="paytype" value='Debit Note'>Debit Note</label></td>
		                    	<td> <label class="radio-inline"><input type="radio" name="paytype" value='Forex'>Forex</label></td>
		                    </tr>
	                    </table>
                  </div>
				</div>
                
				<div class="form-group">
		  			<label class="col-md-2 control-label" for="description">Description</label>  
		  				<div class="col-md-3">
							<input id="description" name="description" type="text" class="form-control input-sm" >
						</div>
          		
		
		 			<label class="col-md-2 control-label" for="ccode">Cost Code</label>  
		 			 	<div class="col-md-3">
			  				<div class='input-group'>
								<input id="ccode" name="ccode" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
			 				</div>
			  			<span class="help-block"></span>
						</div>
				</div>				
								
				<div class="form-group">
					<label class="col-md-2 control-label" for="glaccno">GL Account</label>  
		  				<div class="col-md-3">
			  				<div class='input-group'>
								<input id="glaccno" name="glaccno" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
						<span class="help-block"></span>
						</div>
				</div>
               
				 
				<div class="form-group">
               		<label class="col-md-2 control-label" for="drpayment">Dr. Payment</label>  
			  			<div class="col-md-3">
                        <label class="radio-inline"><input type="radio" name="drpayment" value='1' checked>Yes</label>
                        <label class="radio-inline"><input type="radio" name="drpayment" value='0'>No</label>
			  	</div>
				
                  
                <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
                    <div class="col-md-3">
						<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
					</div>
				</div>
               
                  
                <div class="form-group">
                    <label class="col-md-2 control-label" for="cardflag">Card Flag</label>  
                      <div class="col-md-3">
                        <label class="radio-inline"><input type="radio" name="cardflag" value='1' checked>Yes</label>
                        <label class="radio-inline"><input type="radio" name="cardflag" value='0'>No</label>
                      </div>
                      
                    <label class="col-md-2 control-label" for="valexpdate">ValExpDate</label>                          
                      <div class="col-md-3">
                        <label class="radio-inline"><input type="radio" name="valexpdate" value='1' checked>Yes</label>
                        <label class="radio-inline"><input type="radio" name="valexpdate" value='0'>No</label>
                      </div>
				</div>	

            <div class="form-group" >
             	<label class="col-md-2 control-label" for="cardcent">Card Cent</label>  
	 			 	<div class="col-md-3">
		  				<div class='input-group'>
							<input id="cardcent" name="cardcent" type="text" class="form-control input-sm" data-validation="required">
							<a class='input-group-addon btn btn-primary' id="2shit"><span class='fa fa-ellipsis-h' id-="3"></span></a>
		 				</div>
		  			<span class="help-block" id="4shit"></span>
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
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

						<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

				</div> 

				<div class="form-group">
				<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						  	<div class="col-md-3">
								<input id="ipaddress" name="ipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
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

	<script src="js/finance/Payment Mode/paymentMode.js"></script>

@endsection