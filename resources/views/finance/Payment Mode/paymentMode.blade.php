@extends('layouts.main')

@section('title', 'Payment Mode')

@section('body')
	<input id="source_get" name="source_get" type="hidden" value="{{$_GET['source']}}">
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
		<div class="panel-heading">Payment Mode Setup Header</div>
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
				<input id="source2" name="source" type="hidden" value="{{$_GET['source']}}">
			
				<div class="form-group">
				  <label class="col-md-2 control-label" for="paymode">Payment Mode</label>  
				  <div class="col-md-3">
				  <input id="paymode" name="paymode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" frozeOnEdit>
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="paytype">Payment Type</label>  
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
							<input id="description" name="description" type="text" class="form-control input-sm text-uppercase">
						</div>
          		
		
		 			<label class="col-md-2 control-label" for="ccode">Cost Code</label>  
		 			 	<div class="col-md-3">
			  				<div class='input-group'>
								<input id="ccode" name="ccode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
			 				</div>
			  			<span class="help-block"></span>
						</div>
				</div>				
								
				<div class="form-group">
					<label class="col-md-2 control-label" for="glaccno">GL Account</label>  
		  				<div class="col-md-3">
			  				<div class='input-group'>
								<input id="glaccno" name="glaccno" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
						<span class="help-block"></span>
						</div>

					<label class="col-md-2 control-label" for="glaccno">Commision Rate</label>  
		  			<div class="col-md-3">
							<input id="comrate" name="comrate" type="text" class="form-control input-sm text-uppercase">
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
						<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
						<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
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
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne >
						</div>

						<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" frozeOnEdit hideOne >
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
	<script src="js/finance/Payment Mode/paymentMode.js?v=1.1"></script>

@endsection