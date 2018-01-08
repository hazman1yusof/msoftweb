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
	<input id="scope" name="scope" type="hidden" value="<?php echo $_GET['scope'];?>">

	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>

			<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 5px 0;">
					<div class="form-group"> 
					  <div class="col-md-2">
					  	<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              </div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
								<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">

							<div  id="tunjukname" style="display:none">
								<div class='input-group'>
									<input id="supplierkatdepan" name="supplierkatdepan" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
							
						</div>
		             </div>
				</div>

				<div class="col-md-2">
				  	<label class="control-label" for="Status">Status :</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Confirmed">CONFIRMED</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

			  	<div class="col-md-2">
			  		<label class="control-label" for="trandept">Purchase Department :</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>

			<div id="div_for_but_post" class="col-md-3 col-md-offset-5" style="padding-top: 20px; text-align: end;">
					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div>

			 </fieldset> 
		</form>

    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>

         <div class='col-md-12' style="padding:0px">
        	<div class='click_row'>
        		<label class="control-label">Purchase Order No</label>
        		<span id="ponodepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Purchase Dept</label>
        		<span id="prdeptdepan" style="display: block;">&nbsp</span>
        	</div>
	    </div>

        <div class='col-md-12' style="padding:0 0 15px 0">
	        <table id="jqGrid3" class="table table-striped"></table>
	        <div id="jqGridPager3"></div>
	    </div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<div class='panel panel-info'>
				<div class="panel-heading">
					Purchase Order Header
					<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
				</div>
					<div class="panel-body" style="position: relative;">
						<form class='form-horizontal' style='width:99%' id='formdata'>
							<input id="source" name="source" type="hidden">
							<input id="idno" name="idno" type="hidden">
							<input id="crdbfl" name="crdbfl" type="hidden">
							<input id="isstype" name="isstype" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="purordhd_prdept">Purchase Department</label>
									<div class="col-md-2">
									  <div class='input-group'>
										<input id="purordhd_prdept" name="purordhd_prdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  	</div>
                                 <label class="col-md-2 control-label" for="purordhd_purordno">PO No</label>  
						  			<div class="col-md-2"> 
						  			<input id="purordhd_purordno" name="purordhd_purordno" type="text" class="form-control input-sm" frozeOnEdit hideOne rdonly>
                                     </div>
                             
                                <label class="col-md-2  control-label" for="purordhd_recno">Record No</label>  
						  			<div class="col-md-2">
										<input id="purordhd_recno" name="purordhd_recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
						  		
							</div>

							<div class="form-group">
                            <label class="col-md-2 control-label" for="purordhd_deldept">Delivery Department</label>	 
								 <div class="col-md-2">
									  <div class='input-group'>
										<input id="purordhd_deldept" name="purordhd_deldept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>


                                    <!--  <label class="col-md-2  control-label" for="purordhd_reqdept">Req Dept</label>  
						  			<div class="col-md-2">
										<input id="purordhd_reqdept" name="purordhd_reqdept" type="text" maxlength="11" class="form-control input-sm" frozeOnEdit hideOne rdonly >
						  			</div>-->

								   <label class="col-md-2 control-label" for="purordhd_reqdept">Req Dept</label>	 
								 <div class="col-md-2">
									<div class='input-group'>
										<input id="purordhd_reqdept" name="purordhd_reqdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required" >
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div> 
							

                                       <label class="col-md-2 control-label" for="purordhd_purreqno">Req No</label>	 
								 <div class="col-md-2">
									  <div class='input-group'>
										<input id="purordhd_purreqno" name="purordhd_purreqno" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									 
								  </div>


							</div>

							<div class="form-group">
                              
                              <label class="col-md-2 control-label" for="purordhd_suppcode">Supplier Code</label>	 
								 <div class="col-md-2">
									  <div class='input-group'>
										<input id="purordhd_suppcode" name="purordhd_suppcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

								  
						  			 

                                     <label class="col-md-2 control-label" for="credcode">Creditor</label>	  
								<div class="col-md-2">
									  <div class='input-group'>
										<input id="purordhd_credcode" name="purordhd_credcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>
                            
							</div>
							<hr/>
							
                            
                            <div class="form-group">
                            <label class="col-md-2 control-label" for="purordhd_purdate">PO Date</label>  
						  			<div class="col-md-2">
									<input id="purordhd_purdate" name="purordhd_purdate" type="date" maxlength="10" class="form-control input-sm">
						  		</div>
                             
                             <label class="col-md-2 control-label" for="purordhd_expecteddate">Expected Date</label>  
						  			<div class="col-md-2">
									<input id="purordhd_expecteddate" name="purordhd_expecteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required">
						  		</div>

						  		<label class="col-md-2 control-label" for="termdays">Payment Terms</label>  
						  			<div class="col-md-2"> 
						  			<input id="purordhd_termdays" name="purordhd_termdays" type="text" class="form-control input-sm" data-validation="number" frozeOnEdit hideOne>
						  		</div>
                            </div>

                            <hr/>

                            <div class="form-group">
                              <div class="form-group">
								<label class="col-md-2 control-label" for="purordhd_perdisc">Discount[%]</label>  
						  			<div class="col-md-2">
										<input id="purordhd_perdisc" name="purordhd_perdisc" type="text" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
						  			</div>
						  			<label class="col-md-2 control-label" for="purordhd_amtdisc">Amount Discount</label>	  
						  		<div class="col-md-2">
										<input id="purordhd_amtdisc" name="purordhd_amtdisc" type="text" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
						  			</div>
								
								<label class="col-md-2 control-label" for="purordhd_recstatus">Record Status</label>  
							  <div class="col-md-2">
								<input id="purordhd_recstatus" name="purordhd_recstatus" type="text" class="form-control input-sm" rdonly>
							  </div>
 
                            </div>
                            </div>
                             
                             <div class="form-group">
                             <label class="col-md-2 control-label" for="purordhd_subamount">Sub Amount</label>  
						  			<div class="col-md-2">
										<input id="purordhd_subamount" name="purordhd_subamount" type="text" maxlength="12" class="form-control input-sm" rdonly>
						  			</div>

						  			<label class="col-md-2 control-label" for="purordhd_totamount">Total Amount</label>  
						  			<div class="col-md-2">
										<input id="purordhd_totamount" name="purordhd_totamount" type="text" maxlength="12" class="form-control input-sm" rdonly>
						  			</div>

						  		<label class="col-md-2 control-label" for="TaxClaimable">Tax Claim</label>  
							  <div class="col-md-2">
								<label class="radio-inline"><input type="radio" name="TaxClaimable" value='Claimable' checked>Yes</label><br>
								<label class="radio-inline"><input type="radio" name="TaxClaimable"  value='Non-Claimable'>No</label>
							  </div> 

							   <div class="form-group">
								<label class="col-md-2 control-label" for="purordhd_remarks">Remark</label>   
						  			<div class="col-md-5">
						  				<textarea rows="5" id='purordhd_remarks' name='purordhd_remarks' class="form-control input-sm" ></textarea>
						  			</div>
					    	</div>


                             </div>


					    	<div class="form-group data_info">
						    	<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purreqhd_upduser">Last Entered By</label>  
						  			<input id="purreqhd_upduser" name="purreqhd_upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purreqhd_upddate">Last Entered Date</label>
						  			<input id="purreqhd_upddate" name="purreqhd_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purreqhd_authpersonid">Authorized By</label>  
						  			<input id="purreqhd_authpersonid" name="purreqhd_authpersonid" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purreqhd_authdate">Authorized Date</label>
						  			<input id="purreqhd_authdate" name="purreqhd_authdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
							</div>
					</form>
				</div>
			</div>

			<div class='panel panel-info'>
				<div class="panel-heading">Purchase Order Detail</div>
				<input id="gstpercent" name="gstpercent" type="hidden">
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
						<input id="gstpercent" name="gstpercent" type="hidden">
							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
					</div>

					<input id="gstpercent" name="gstpercent" type="hidden">
					<div class="panel-body">
						<div class="noti"><ol></ol>
						</div>
					</div>

			</div>

			<div id="dialog_remarks" title="Remarks">
			  <div class="panel panel-default">
			    <div class="panel-body">
			    	<textarea id='remarks2' name='remarks2' rows='6' class="form-control input-sm" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div>
		</div>
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="purOrder3.js"></script>
	<!--<script src="pdfgen.js"></script>-->
	<script src="../../../../assets/js/utility.js"></script>
	<!--<script src="../../../../assets/plugins/pdfmake/pdfmake.min.js"></script>-->
	<!--<script src="../../../../assets/plugins/pdfmake/vfs_fonts.js"></script>-->
	 <script src="../../../../assets/js/dialogHandler.js"></script> 

<script>
		
</script>
</body>
</html>