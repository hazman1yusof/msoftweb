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
			  		<label class="control-label" for="trandept">Store / Dept</label> 
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
								<label class="col-md-2 control-label" for="reqdept">Request Department</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="reqdept" name="reqdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

						  		<label class="col-md-2 control-label" for="recno">Request No.</label>  
						  			<div class="col-md-2">
										<input id="recno" name="recno" type="text" maxlength="30" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="reqtodept">Request Made To.</label>
								<div class="col-md-4">
									<div class='input-group'>
										<input id="reqtodept" name="reqtodept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

						  		<label class="col-md-2 control-label" for="ivreqno">Record No.</label>  
						  			<div class="col-md-2">
										<input id="ivreqno" name="ivreqno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
							</div>
							<hr/>
							<div class="form-group">
						  		<label class="col-md-2 control-label" for="reqtype">Request Type</label>
						  			<div class="col-md-3">
										<input id="reqtype" name="reqtype" type="text" maxlength="11" class="form-control input-sm" value="TRANSFER" rdonly>
						  			</div>

					  			<label class="col-md-2 control-label" for="reqdt">Request Date</label>
					  			<div class="col-md-3">
									<input id="reqdt" name="reqdt" data-validation="required" type="date" class="form-control input-sm">
					  			</div>
							</div>

							<div class="form-group">
						  		<label class="col-md-2 control-label" for="amount">Amount</label>  
						  			<div class="col-md-3">
										<input id="amount" name="amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.0000" value='0.00' rdonly>
						  			</div>
						  			
						  		<label class="col-md-2 control-label" for="recstatus">Status</label>  
								  	<div class="col-md-3">
											<input id="recstatus" name="recstatus" maxlength="10" class="form-control input-sm" rdonly>
					 				</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="remarks">Remark</label>   
						  			<div class="col-md-8">
						  				<textarea id='remarks' name='remarks' class="form-control input-sm" ></textarea>
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
	<script src="stockReq1.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!-- <script src="../../../../assets/js/dialogHandler.js"></script> -->

<script>
		
</script>
</body>
</html>