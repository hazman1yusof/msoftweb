<div id="dialogForm_paymentVoucher" class="dialogdtl" title="Add Form" style="display:none;">
	<div class='panel panel-info'>
		<div class="panel-heading">Payment Voucher/Deposit Header</div>
		<div class="panel-body" style="position: relative;">
			<form class='form-horizontal' style='width:99%' id='formdata_paymentVoucher'>

				<div class="form-group">
					<label class="col-md-2 control-label" for="actdate">Date</label>  
			  			<div class="col-md-2" id="actdate">
							<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" readonly>
			  			</div>

			  		<label class="col-md-2 control-label" for="pvno">PV No</label>  
			  			<div class="col-md-2">
							<input id="pvno" name="pvno" type="text" class="form-control input-sm text-uppercase" maxlength="30" readonly>
			  			</div>

			  		<label class="col-md-2 control-label" for="auditno">Audit No</label>  
			  			<div class="col-md-2">
			  				<input id="auditno" name="auditno" type="text" class="form-control input-sm" readonly>
			  			</div>	
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="trantype">Transaction Type</label> 
						<div class="col-md-2">
						  	<select id="trantype" name=trantype class="form-control" data-validation="required" disabled>
						       <option value="PV">Payment Voucher</option>
						       <option value="PD">Payment Deposit</option>
						    </select>
					  	</div>

					<label class="col-md-2 control-label" for="document">Document No</label>  
			  			<div class="col-md-2">
							<input id="document" name="document" type="text" maxlength="30" class="form-control input-sm text-uppercase" readonly>
			  			</div>

					<label class="col-md-2 control-label" for="paymode">Paymode</label>	 
					 	<div class="col-md-2">
						  	<div class='input-group'>
								<input id="paymode" name="paymode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" readonly>
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						  	</div>
						  	<span class="help-block"></span>
					  	</div>
				
				</div>

				<div class="form-group">

					<label class="col-md-2 control-label" for="bankcode" id="bankcode_parent">Bank Code</label>	 
					 	<div class="col-md-2">
						  	<div class='input-group'>
								<input id="bankcode" name="bankcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" readonly>
								<a class='input-group-addon btn btn-primary' id="bankcode_dh"><span class='fa fa-ellipsis-h'></span></a>
						  	</div>
						  	<span class="help-block" ></span>
					</div>	  	

					<label class="col-md-2 control-label" for="cheqno" id="cheqno_parent">Cheque No</label>	  
			  			<div class="col-md-2">
						  	<div class='input-group'>
								<input id="cheqno" name="cheqno" type="text" maxlength="12" class="form-control input-sm text-uppercase" readonly>
								<a class='input-group-addon btn btn-primary' id="cheqno_dh"><span class='fa fa-ellipsis-h'></span></a>
						  	</div>
						  	<span class="help-block"></span>
					  	</div>

					<label class="col-md-2 control-label" for="cheqdate" id="cheqdate_parent">Cheque Date</label>  
			  			<div class="col-md-2" id="cheqdate">
							<input id="cheqdate" name="cheqdate" type="date" maxlength="12" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" readonly>
			  			</div>
						
				</div>

				<hr/>

				<div class="form-group">
		    		<label class="col-md-2 control-label" for="remarks">Remarks</label> 
		    			<div class="col-md-8"> 
		    				<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" readonly ></textarea>
		    			</div>
		   		</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="suppcode">Pay To (In Invoice)</label>	  
						<div class="col-md-3">
						  	<div class='input-group'>
								<input id="suppcode" name="suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" readonly>
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						  	</div>
						  	<span class="help-block"></span>
					  	</div>
				
					<label class="col-md-2 control-label" for="payto">Pay To</label>	  
						<div class="col-md-3">
						  	<div class='input-group'>
								<input id="payto" name="payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" readonly>
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						  	</div>
						  	<span class="help-block"></span>
					  	</div>
				</div>		  	

				<div class="form-group">
					<label class="col-md-2 control-label" for="amount">Total Amount</label>  
				  		<div class="col-md-3">
							<input id="amount" name="amount" maxlength="12" class="form-control input-sm" data-validation="required" readonly> 
	 					</div>
				</div>
			</form>
			<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			</div>
		</div>
	</div>
		

	<div class='panel panel-info' id="pvpd_detail">
		<div class="panel-heading">Payment Voucher/Deposit Detail</div>
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