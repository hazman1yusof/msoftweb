<?php 
	include_once('../../../../header.php'); 
?>
<style>
	.data_info{
		text-align: center;
		color: #286090;
		background: #d9edf7;
		width: 400px;
		height: 120px;
	    margin: 0px !important;
	    padding: 5px !important;
	    border-top-left-radius: 30px;
	    position: absolute;
	    bottom: 0px;
	    right: 0px;
	    cursor: pointer;
	    display: block;
	}
	.click_row{
		width:15%;
		display: inline-block;
		padding:0 5px 1px 0;
		background: beige;
    	margin: 5px;
    	border-radius: 5px;
    	text-align: center;
	    cursor: pointer;
	}
	.click_row:hover{
		opacity: 0.7;
	}
	.help-block{
		margin: 0 !important;
	}
</style>
<body style="display:none">

	<input id="x" name="x" type="hidden" value="<?php echo $_SESSION['deptcode'];?>">

	 
	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
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
			  		<label class="control-label" for="trandept">Purchase Dept</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>

				<div id="div_for_but_post" class="btn-group" style="position: absolute; padding: 20px; bottom: 0;right: 0;display: none;">
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq">CANCEL</button>
				</div>

			 </fieldset> 
		</form>
	
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>

        <div class='col-md-12' style="padding:0px">
        	<div class='click_row'>
        		<label class="control-label">Record No</label>
        		<span id="recnodepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Purchase Dept</label>
        		<span id="reqdeptdepan" style="display: block;">&nbsp</span>
        	</div>
	    </div>

         <div class='col-md-12' style="padding:0 0 15px 0">
	            <table id="jqGrid3" class="table table-striped"></table>
	            <div id="jqGridPager3"></div>
	    </div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Delivery Order Header
					<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
					</div>
				<div class="panel-body" style="position: relative;">
					<form class='form-horizontal' style='width:99%' id='formdata'>
							<input id="delordhd_trantype" name="delordhd_trantype" type="hidden">
							<input id="delordhd_idno" name="delordhd_idno" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="delordhd_prdept">Purchase Department</label>	 
								<div class="col-md-4">
									<div class='input-group'>
									<input id="delordhd_prdept" name="delordhd_prdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									  <span class="help-block"></span>
								  </div>

						  		<label class="col-md-1 control-label" for="delordhd_">PO No</label>  
						  		<div class="col-md-2"> <!--- value="<?php// echo "auditno";?>" -->
						  			<input id="delordhd_" name="delordhd_" type="text" class="form-control input-sm" rdonly>
						  		</div>

						  		<label class="col-md-1 control-label" for="delordhd_docno">GRN No</label>  
						  		<div class="col-md-2"> <!--- value="<?php// echo "auditno";?>" -->
						  			<input id="delordhd_docno" name="delordhd_docno" type="text" class="form-control input-sm" rdonly>
						  		</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="delordhd_suppcode">Supplier Code</label>	 
								 <div class="col-md-4">
									  <div class='input-group'>
										<input id="delordhd_suppcode" name="delordhd_suppcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

								<label class="col-md-1 control-label" for="delordhd_delordno">DO No</label>  
						  		<div class="col-md-2"> <!--- value="<?php// echo "auditno";?>" -->
						  			<input id="delordhd_delordno" name="delordhd_delordno" type="text" class="form-control input-sm">
						  		</div>

						  		<label class="col-md-1 control-label" for="delordhd_recno">Record No</label>  
						  		<div class="col-md-2"> <!--- value="<?php// echo "auditno";?>" -->
						  			<input id="delordhd_recno" name="delordhd_recno" type="text" class="form-control input-sm" rdonly>
						  		</div>
						  	</div>

						  	<div class="form-group">
								<label class="col-md-2 control-label" for="delordhd_deldept">Delivery Department</label>
								<div class="col-md-4">
									  <div class='input-group'>
										<input id="delordhd_deldept" name="delordhd_deldept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								</div>

								<label class="col-md-1 control-label" for="delordhd_credcode">Creditor</label>	  
								<div class="col-md-2">
									  <div class='input-group'>
										<input id="delordhd_credcode" name="delordhd_credcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								</div>

								<label class="col-md-1 control-label" for="delordhd_invoiceno">Invoice No</label>  
						  		<div class="col-md-2">
									<input id="delordhd_invoiceno" name="delordhd_invoiceno" type="text" maxlength="10" class="form-control input-sm" rdonly>
						  		</div>
						  	</div>

						  	<hr/>

						  	<div class="form-group">		
						  		<label class="col-md-2 control-label" for="delordhd_trandate">Received Date</label>  
						  		<div class="col-md-2">
									<input id="delordhd_trandate" name="delordhd_trandate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
						  		</div>

						  		<label class="col-md-2 control-label" for="delordhd_trantime">Received Time</label>  
					  			<div class="col-md-2">
									<input id="delordhd_trantime" name="delordhd_trantime" type="time" class="form-control input-sm">
					  			</div>

					  			<label class="col-md-2 control-label" for="delordhd_deldate">Delivery Date</label>  
						  			<div class="col-md-2">
									<input id="delordhd_deldate" name="delordhd_deldate" type="date" maxlength="10" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
						  		</div>
							</div>

							<hr/>

							<div class="form-group">
								<label class="col-md-2 control-label" for="delordhd_subamount">Sub Amount</label>  
						  			<div class="col-md-2">
										<input id="delordhd_subamount" name="delordhd_subamount" type="text" maxlength="12" class="form-control input-sm" rdonly>
						  			</div>
						  		<label class="col-md-2 control-label" for="delordhd_amtdisc">Amount Discount</label>	  
						  			<div class="col-md-2">
										<input id="delordhd_amtdisc" name="delordhd_amtdisc" type="text" maxlength="12" class="form-control input-sm">
						  			</div>
								<label class="col-md-2 control-label" for="delordhd_totamount">Total Amount</label>  
						  			<div class="col-md-2">
										<input id="delordhd_totamount" name="delordhd_totamount" type="text" maxlength="12" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
						  		
							 	<label class="col-md-2 control-label" for="delordhd_">GST Amount</label>  
							  	<div class="col-md-2">
										<input id="delordhd_" name="delordhd_" maxlength="12" class="form-control input-sm" rdonly>  <!--data-validation-allowing="float" -->
				 				</div>

				 				<label class="col-md-2 control-label" for="delordhd_taxclaimable">Tax Claim</label>  
								  <div class="col-md-2">
									<label class="radio-inline"><input type="radio" name="delordhd_taxclaimable" data-validation="required" value='Claimable'>Yes</label>
									<label class="radio-inline"><input type="radio" name="delordhd_taxclaimable" data-validation="required" value='Non-Claimable' selected>No</label>
								  </div>

							  <label class="col-md-2 control-label" for="delordhd_recstatus">Record Status</label>  
							  <div class="col-md-2">
								<input id="delordhd_recstatus" name="delordhd_recstatus" type="text" class="form-control input-sm" rdonly>
							  </div>
							</div>	

							<hr/>

							<div class="form-group">
							  <label class="col-md-2 control-label" for="delordhd_respersonid">Certified By</label> 
							  <div class="col-md-2">
								  <div class='input-group'>
									<input id="delordhd_respersonid" name="delordhd_respersonid" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								  </div>
								  <span class="help-block"></span>
							  </div> 
							</div>

							<div class="form-group">
					    		<label class="col-md-2 control-label" for="delordhd_remarks">Remarks</label> 
					    		<div class="col-md-5"> 
					    		<textarea class="form-control input-sm" name="delordhd_remarks" rows="2" cols="55" maxlength="400" id="delordhd_remarks" ></textarea>
					    		</div>
					    	
					   		</div>

							<div class="form-group data_info">
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_adduser">Entered By</label>  
						  			<input id="delordhd_adduser" name="delordhd_adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_adddate">Entered Date</label>
						  			<input id="delordhd_adddate" name="delordhd_adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
						    	<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_upduser">Last Entered By</label>  
						  			<input id="delordhd_upduser" name="delordhd_upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_upddate">Last Entered Date</label>
						  			<input id="delordhd_upddate" name="delordhd_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
							</div>
					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Delivery Order Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<input id="gstpercent" name="gstpercent" type="hidden">

							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti"><ol></ol>
						</div>
					</div>
			</div>
		</div>
		
		

	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level-->
	<script src="deliveryOrder.js"></script>
	<script src="pdfgen.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/plugins/pdfmake/pdfmake.min.js"></script>
	<script src="../../../../assets/plugins/pdfmake/vfs_fonts.js"></script>
	<!--<script src="../../../../assets/js/dialogHandler.js"></script>-->

<script>
		
</script>
</body>
</html>