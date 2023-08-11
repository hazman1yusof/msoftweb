<div id="dialogForm_SalesOrder" title="Add Form" class="dialogdtl" style="display:none;">
	<div class='panel panel-info'>
		<div class="panel-heading">Sales Order Header</div>
		<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
			<form class='form-horizontal' style='width:99%' id='formdata_SalesOrder'>
				<input id="idno" name="idno" type="hidden">
				<input id="source" name="source" type="hidden">
				<input id="trantype" name="trantype" type="hidden">
				<input id="pricebilltype" name="pricebilltype" type="hidden">


				<div class="form-group">
					<label class="col-md-2 control-label" for="deptcode">Store Dept</label>	 
					<div class="col-md-4">
						<div class='input-group'>
						<input id="deptcode" name="deptcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
							<span class="help-block"></span>
						</div>

					<label class="col-md-1 control-label" for="invno">Invoice No</label>  
					<div class="col-md-2">
						<input id="invno" name="invno" type="text" class="form-control input-sm" rdonly>
					</div>

					<label class="col-md-1 control-label" for="entrydate">Document Date</label>  
					<div class="col-md-2">
						<input id="entrydate" name="entrydate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value"  value="{{Carbon\Carbon::now()->format('Y-m-d')}}" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="debtorcode">Customer</label>	 
					<div class="col-md-4">
						<div class='input-group'>
						<input id="debtorcode" name="debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>

					<label class="col-md-1 control-label" for="hdrtype">Bill Type</label>  
					<div class="col-md-2"> 
						<div class='input-group'>
							<input id="hdrtype" name="hdrtype" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>							
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="termdays">Term</label>  
					<div class="col-md-1">
						<input id="termdays" name="termdays" type="text" value ="30" class="form-control input-sm">
					</div>

					<div class="col-md-2">
						<select class="form-control col-md-3" id='termmode' name='termmode' data-validation="required" data-validation-error-msg="Please Enter Value">
							<option value='DAYS' selected>DAYS</option>
							<option value='MONTH'>MONTH</option>
							<option value='YEAR'>YEAR</option>
						</select> 
					</div>

					<label class="col-md-2 control-label" for="posteddate">Posted Date</label>  
					<div class="col-md-2">
						<input id="posteddate" name="posteddate" type="text" maxlength="10" class="form-control input-sm" max="<?php echo date("Y-m-d"); ?>" rdonly>
					</div>

				</div>

				<div class="form-group">		
					<label class="col-md-2 control-label" for="orderno">Order No</label>  
					<div class="col-md-2"> 
						<input id="orderno" name="orderno" type="text" class="form-control input-sm text-uppercase" >
					</div>
					
					<label class="col-md-3 control-label" for="auditno">Auto No</label>  
					<div class="col-md-2"> 
						<input id="auditno" name="auditno" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" rdonly>
					</div>
				</div>

				<hr>
					
				<div class="form-group">		
					<label class="col-md-2 control-label" for="ponum">PO No</label>  
					<div class="col-md-2"> 
						<input id="ponum" name="ponum" type="text" class="form-control input-sm text-uppercase">
					</div>

					<label class="col-md-3 control-label" for="podate">PO Date</label>  
					<div class="col-md-2">
						<input id="podate" name="podate" type="date" maxlength="10" class="form-control input-sm" value="" max="<?php echo date("Y-m-d"); ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="amount">Total Amount</label>
					<div class="col-md-2">
						<input id="amount" name="amount" type="text" maxlength="11" class="form-control input-sm" value="0.00" rdonly>
					</div>

					<label class="col-md-3 control-label" for="recstatus">Record Status</label>  
					<div class="col-md-2">
							<input id="recstatus" name="recstatus" maxlength="10" class="form-control input-sm" rdonly>
					</div>
				</div>

				<hr>

				<div class="form-group">
					<label class="col-md-2 control-label" for="remark">Remarks</label> 
					<div class="col-md-6"> 
					<textarea class="form-control input-sm text-uppercase" name="remark" rows="5" cols="55" maxlength="400" id="remark" ></textarea>
					</div>
				</div>

				<div class="form-group data_info">
					<div class="col-md-6 minuspad-13">
							<label class="control-label" for="adduser">Last Entered By</label>  
				  			<input id="adduser" name="adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
		  			</div>
		  			<div class="col-md-6 minuspad-13">
						<label class="control-label" for="adddate">Last Entered Date</label>
			  			<input id="adddate" name="adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
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
			</form>
		</div>
	</div>
		
	<div class='panel panel-info'>
		<div class="panel-heading">Sales Order Detail</div>
		<div class="panel-body">
			<form class='form-vertical' style='width:99%'>
				<div id="jqgrid2_salesorder_c" class='col-md-12'>
					<table id="jqGrid2_salesorder" class="table table-striped"></table>
		            <div id="jqGrid2_salesorderPager"></div>
				</div>
			</form>
		</div>
	</div>
</div>