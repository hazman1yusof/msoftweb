<?php 
	include_once('../../../../header.php'); 
?>
<style>
	.data_info{
		text-align: center;
		color: #286090;
		background: #d9edf7;
		width: 380px;
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
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>

			<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 5px 0;">
					<div class="form-group"> 
					  <div class="col-md-2">
					  	<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              </div>

					  	<div class="col-md-6 input-group" style="padding-top:20px;">
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

				<div id="div_for_but_post" class="col-md-3 col-md-offset-5" style="padding-top: 20px; text-align: end;">
					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div>

			 </fieldset> 
		</form>

		<div class="panel panel-default">
		    <div class="panel-heading">Stock Request Header</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            				<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>

    	<!-- <div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div> -->

       <!--  <div class='col-md-12' style="padding:0px"> -->
        	<div class='click_row'>
				<label class="control-label">Request Dept</label>
        		<span id="reqdeptdepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
        		<label class="control-label">Record No</label>
        		<span id="ivreqnodepan" style="display: block;">&nbsp</span>
        	</div>
	    <!-- </div> -->

	    <div class="panel panel-default">
		    <div class="panel-heading">Stock Request Detail</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
	            		<table id="jqGrid3" class="table table-striped"></table>
	            			<div id="jqGridPager3"></div>
	    			</div>'
		    	</div>
		</div>

        <!-- <div class='col-md-12' style="padding:0 0 15px 0">
	        <table id="jqGrid3" class="table table-striped"></table>
	        <div id="jqGridPager3"></div>
	    </div> -->
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<div class='panel panel-info'>
				<div class="panel-heading">Stock Request Header
					<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
				</div>
					<div class="panel-body" style="position: relative;">
						<form class='form-horizontal' style='width:99%' id='formdata'>
							<input id="source" name="source" type="hidden">
							<input id="idno" name="idno" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="reqdept">Request Department</label>
								<div class="col-md-3">
									<div class='input-group'>
										<input id="reqdept" name="reqdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

						  		<label class="col-md-2 control-label" for="ivreqno">Request No.</label>  
						  			<div class="col-md-2">
										<input id="ivreqno" name="ivreqno" type="text" maxlength="30" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="reqtodept">Request Made To.</label>
								<div class="col-md-3">
									<div class='input-group'>
										<input id="reqtodept" name="reqtodept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

						  		<label class="col-md-2 control-label" for="recno">Record No.</label>  
						  			<div class="col-md-2">
										<input id="recno" name="recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<!-- <label class="col-md-2 control-label" for="reqtype">Request Type</label>
						  			<div class="col-md-2">
										<input id="reqtype" name="reqtype" type="text" maxlength="11" class="form-control input-sm" value="TRANSFER" rdonly>
						  			</div> -->

						  		<label class="col-md-2 control-label" for="reqdt">Request Date</label>
						  			<div class="col-md-2">
									<input id="reqdt" name="reqdt" data-validation="required" type="date" min="<?php $backday= 3; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" max="<?php echo date('Y-m-d');?>" class="form-control input-sm">
							  	</div>
							</div>

							<hr/>
							<div class="form-group">
						  		<label class="col-md-2 control-label" for="amount">Amount</label>  
						  			<div class="col-md-2">
										<input id="amount" name="amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.0000" value='0.00' rdonly>
						  			</div>

					  			<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
								<div class="col-md-2">
										<input id="recstatus" name="recstatus" maxlength="10" class="form-control input-sm" rdonly>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="remarks">Remark</label>   
						  			<div class="col-md-6">
						  				<textarea rows="5" id='remarks' name='remarks' class="form-control input-sm" ></textarea>
						  			</div>
					    	</div>

					    	<div class="form-group data_info">
						    	<div class="col-md-6 minuspad-13">
									<label class="control-label" for="upduser">Last Entered By</label>  
						  			<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="upddate">Last Entered Date</label>
						  			<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="authpersonid">Authorized By</label>  
						  			<input id="authpersonid" name="authpersonid" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="authdate">Authorized Date</label>
						  			<input id="authdate" name="authdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
							</div>
					</form>
				</div>
			</div>

			<div class='panel panel-info'>
				<div class="panel-heading">Stock Request Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
					</div>

					<input id="convfactor_uom" name="convfactor_uom" type="hidden" value='1'>
					<input id="convfactor_pouom" name="convfactor_pouom" type="hidden" value='1'>
					<!-- <input id="gstpercent" name="gstpercent" type="hidden"> -->
					<div class="panel-body">
						<div class="noti" style="color: rgb(169, 68, 66)"><ol></ol>
						</div>
					</div>

			</div>

			<!-- <div id="dialog_remarks" title="Remarks">
			  <div class="panel panel-default">
			    <div class="panel-body">
			    	<textarea id='remarks2' name='remarks2' rows='6' class="form-control input-sm" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div> -->
		</div>
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="stockReq.js"></script>
	<script src="../../../../assets/js/utility.js"></script>

<script>
		
</script>
</body>
</html>