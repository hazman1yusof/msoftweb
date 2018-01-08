<?php 
	include_once('../../../../header.php'); 
?>
<style>
	.sajanktry{
		text-align: center;
		color: white;
		background: #286090;
	    width: 30px;
	    height: 30px;
	    margin: 0px !important;
	    padding: 5px !important;
	    border-top-left-radius: 30px;
	    position: absolute;
	    bottom: 0px;
	    right: 0px;
	    cursor: pointer;
	}
	.sajanktry div{
  		display: none;
	}
	.help-block{
		margin: 0 !important;
	}
</style>
<body style="display:none">

	<input id="x" name="x" type="hidden" value="<?php echo $_SESSION['deptcode'];?>">

	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>

			<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
					  <div class="col-md-2">
					  	<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              </div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
								<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
						</div>
		             </div>
				</div>

				<div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Confirmed">CONFIRMED</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

			  	<div class="col-md-2">
			  		<label class="control-label" for="trandept">Purchase Department</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>

				<div id="div_for_but_post" class="col-md-3 col-md-offset-5" style="padding-top: 20px; text-align: end;display: none;">
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq">CANCEL</button>
				</div>

			 </fieldset> 
		</form>

    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>

        <div class='col-md-12' style="padding:0 0 15px 0">
	        <table id="jqGrid3" class="table table-striped"></table>
	        <div id="jqGridPager3"></div>
	    </div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Purchase Order Header</div>
				<div class="panel-body">

					<form class='form-horizontal' style='width:99%' id='formdata'>

						<div class="prevnext btn-group pull-right">
						</div>
								<!--<input id="apacthdr_source" name="apacthdr_source" type="hidden" value="AP">
								<input id="apacthdr_trantype" name="apacthdr_trantype" type="hidden">-->

						<div class="form-group">

							<div class="form-group">
								<label class="col-md-1 control-label" for="prdept">Purchase Department</label>	 
								 <div class="col-md-2">
									  <div class='input-group'>
										<input id="prdept" name="prdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

								  <label class="col-md-1 control-label" for="potype">PO Type</label>  
						  			<div class="col-md-2"> 
						  			<input id="potype" name="potype" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
                                

						  		<label class="col-md-1 control-label" for="delordhd_">PO No</label>  
						  			<div class="col-md-2"> 
						  			<input id="delordhd_" name="delordhd_" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
                                
                                <label class="col-md-1 control-label" for="recno">Record No</label>  
						  			<div class="col-md-2"> 
						  			<input id="recno" name="recno" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
						  		
							</div>
                               
                               <div class="form-group">
								<label class="col-md-1 control-label" for="deldept">Delivery Department</label>	 
								 <div class="col-md-2">
									  <div class='input-group'>
										<input id="deldept" name="deldept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

								  <label class="col-md-1 control-label" for="delordhd_">Req Type</label>  
						  			<div class="col-md-2"> 
						  			<input id="delordhd_" name="delordhd_" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
                                

						  		<label class="col-md-1 control-label" for="purreqno">Req No</label>  
						  			<div class="col-md-2"> 
						  			<input id="purreqno" name="purreqno" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
                                
                                <label class="col-md-1 control-label" for="termdays">Payment Terms</label>  
						  			<div class="col-md-2"> 
						  			<input id="termdays" name="termdays" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
						  		
							</div>

							 <div class="form-group">
								<label class="col-md-1 control-label" for="suppcode">Supplier Code</label>	 
								 <div class="col-md-2">
									  <div class='input-group'>
										<input id="suppcode" name="suppcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

								  
						  			<div class="col-md-3"> 
						  			<input id="suppcode" name="suppcode" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
                                

						  		<label class="col-md-1 control-label" for="credcode">Creditor</label>	  
								<div class="col-md-2">
									  <div class='input-group'>
										<input id="credcode" name="credcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>
						  		
							</div>
                                
                                <div class="form-group">		
						  		<label class="col-md-1 control-label" for="delordhd_trandate">PO Date</label>  
						  			<div class="col-md-2">
									<input id="delordhd_trandate" name="delordhd_trandate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
						  		</div>
							

							
						  		<label class="col-md-1 control-label" for="expecteddate">Expected Date</label>  
						  			<div class="col-md-2">
									<input id="expecteddate" name="expecteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
						  		</div>

						  		
						  		<label class="col-md-4 control-label" for="delordhd_TaxClaimable">Tax Claim</label>  
							  <div class="col-md-2">
								<label class="radio-inline"><input type="radio" name="delordhd_TaxClaimable" data-validation="required" value='Claimable'>Yes</label><br>
								<label class="radio-inline"><input type="radio" name="delordhd_TaxClaimable" data-validation="required" value='Non-Claimable'>No</label>
							  </div>
							</div>
						
						       <div class="form-group">
								<label class="col-md-1 control-label" for="delordhd_subamount">Discount[%]</label>  
						  			<div class="col-md-2">
										<input id="delordhd_subamount" name="delordhd_subamount" type="text" maxlength="12" class="form-control input-sm">
						  			</div>

						  		<label class="col-md-1 control-label" for="delordhd_amtdisc">Amount Discount</label>	  
						  		<div class="col-md-2">
										<input id="delordhd_amtdisc" name="delordhd_amtdisc" type="text" maxlength="12" class="form-control input-sm">
						  			</div>
								
								<label class="col-md-4 control-label" for="delordhd_recstatus">Record Status</label>  
							  <div class="col-md-2">
								<input id="delordhd_recstatus" name="delordhd_recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
							  </div> 
						
							</div>

						       <div class="form-group">
								<label class="col-md-1 control-label" for="delordhd_subamount">Sub Amount</label>  
						  			<div class="col-md-2">
										<input id="delordhd_subamount" name="delordhd_subamount" type="text" maxlength="12" class="form-control input-sm">
						  			</div>

						  		
								
								<label class="col-md-1 control-label" for="delordhd_totamount">Total Amount</label>  
						  			<div class="col-md-2">
										<input id="delordhd_totamount" name="delordhd_totamount" type="text" maxlength="12" class="form-control input-sm">
						  			</div>    
						
							</div>
                                
                                <div class="form-group">
					    	<label class="col-md-1 control-label" for="delordhd_remarks">Remarks</label> 
					    		<div class="col-md-8"> 
					    		<textarea class="form-control input-sm" name="delordhd_remarks" rows="2" cols="55" maxlength="400" id="delordhd_remarks" ></textarea>
					    		</div>
					    	</div>
					   			 </div>

							<div class="form-group sajanktry">
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_adduser">Entered By</label>  
						  			<input id="delordhd_adduser" name="delordhd_adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_adddate">EnteredDate</label>
						  			<input id="delordhd_adddate" name="delordhd_adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
						    	<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_authpersonid">Confirm By</label>  
						  			<input id="delordhd_authpersonid" name="delordhd_authpersonid" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_authdate">Confirm Date</label>
						  			<input id="delordhd_authdate" name="delordhd_authdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
							</div>
							 <!-- <button type="button" id='cancel' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Cancel</button> -->
							<button type="button" id='save' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;display: none;'>Save</button>



							
					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Purchase Order Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti"></div>
					</div>

			</div>
		</div>
		
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="purOrder.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!-- <script src="../../../../assets/js/dialogHandler.js"></script> -->

<script>
		
</script>
</body>
</html>