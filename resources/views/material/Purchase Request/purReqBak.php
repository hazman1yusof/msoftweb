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
				<div class="panel-heading">Inventory DataEntry Header</div>
					<div class="panel-body" style="position: relative;">
						<form class='form-horizontal' style='width:99%' id='formdata'>
							<input id="source" name="source" type="hidden">
							<input id="idno" name="idno" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_reqdept">Request Department</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="purreqhd_reqdept" name="purreqhd_reqdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

						  		<label class="col-md-2 control-label" for="purreqhd_purreqno">Request No.</label>  
						  			<div class="col-md-2">
										<input id="purreqhd_purreqno" name="purreqhd_purreqno" type="text" maxlength="30" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_prdept">Purchase Department</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="purreqhd_prdept" name="purreqhd_prdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

						  		<label class="col-md-2 control-label" for="purreqhd_recno">Record No.</label>  
						  			<div class="col-md-2">
										<input id="purreqhd_recno" name="purreqhd_recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_suppcode">Supplier Code</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="purreqhd_suppcode" name="purreqhd_suppcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>
							</div>

							<hr/>
							<div class="form-group">
						  		<label class="col-md-2 control-label" for="purreqhd_perdisc">Discount (%)</label>
						  			<div class="col-md-3">
										<input id="purreqhd_perdisc" name="purreqhd_perdisc" type="text" maxlength="11" class="form-control input-sm" value="0.00">
						  			</div>

					  			<label class="col-md-2 control-label" for="purreqhd_amtdisc">Amount Discount</label>
					  			<div class="col-md-3">
									<input id="purreqhd_amtdisc" name="purreqhd_amtdisc" type="text" class="form-control input-sm" value="0.00">
					  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_subamount">Subamount</label>
						  			<div class="col-md-3">
										<input id="purreqhd_subamount" name="purreqhd_subamount" type="text" maxlength="11" class="form-control input-sm" value="0.00">
						  			</div>

						  		<label class="col-md-2 control-label" for="purreqhd_totamount">Total Amount</label>  
						  			<div class="col-md-3">
										<input id="purreqhd_totamount" name="purreqhd_totamount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.0000" value='0.00' rdonly>
						  			</div>
						  			
							</div>

							<div class="form-group">
					  			<label class="col-md-2 control-label" for="purreqhd_purreqdt">Request Date</label>
					  			<div class="col-md-3">
									<input id="purreqhd_purreqdt" name="purreqhd_purreqdt" data-validation="required" type="date" class="form-control input-sm">
					  			</div>

								<label class="col-md-2 control-label" for="purreqhd_recstatus">Status</label>  
								<div class="col-md-3">
										<input id="purreqhd_recstatus" name="purreqhd_recstatus" maxlength="10" class="form-control input-sm" rdonly>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="purreqhd_remarks">Remark</label>   
						  			<div class="col-md-8">
						  				<textarea id='purreqhd_remarks' name='purreqhd_remarks' class="form-control input-sm" ></textarea>
						  			</div>
					    	</div>

					    	<div class="form-group sajanktry">
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="adduser">Entered By</label>  
						  			<input id="adduser" name="adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="adddate">EnteredDate</label>
						  			<input id="adddate" name="adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
						    	<div class="col-md-6 minuspad-13">
									<label class="control-label" for="authpersonid">Confirm By</label>  
						  			<input id="authpersonid" name="authpersonid" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="authdate">Confirm Date</label>
						  			<input id="authdate" name="authdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
							</div>
					</form>
				</div>
			</div>

			<div class='panel panel-info'>
				<div class="panel-heading">Inventory DataEntry Detail</div>
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
	<script src="purReqTest.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!-- <script src="../../../../assets/js/dialogHandler.js"></script> -->

<script>
		
</script>
</body>
</html>