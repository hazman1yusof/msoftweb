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
	}</style>
<body style="display:none">

	<input id="x" name="x" type="hidden" value="<?php echo $_SESSION['deptcode'];?>">

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

					  	<div class="col-md-6 input-group" style="padding-top:20px;">
					  		<span class="input-group-addon"><i class="fa fa-search"></i></span>
							<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
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
			  		<label class="control-label" for="trandept">Store / Dept :</label> 
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

		<div class="panel panel-default">
		    	<div class="panel-heading">Inventory DataEntry Header</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

		
        	<div class='click_row'>
        		<label class="control-label">Dept</label>
        		<span id="txndeptdepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Type</label>
        		<span id="trantypedepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Document No</label>
        		<span id="docnodepan" style="display: block;">&nbsp</span>
        	</div>

        <div class="panel panel-default">
		    	<div class="panel-heading">Inventory DataEntry Detail</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
	            			<table id="jqGrid3" class="table table-striped"></table>
	            			<div id="jqGridPager3"></div>
	    				</div>'
		    		</div>
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<div class='panel panel-info'>
				<div class="panel-heading">
					Inventory DataEntry Header
					<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
				</div>
					<div class="panel-body" style="position: relative;">
						<form class='form-horizontal' style='width:99%' id='formdata'>
							<input id="source" name="source" type="hidden">
							<input id="idno" name="idno" type="hidden">
							<input id="crdbfl" name="crdbfl" type="hidden">
							<input id="isstype" name="isstype" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="txndept">Transaction Department</label>
									<div class="col-md-3">
									  <div class='input-group'>
										<input id="txndept" name="txndept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  	</div>

								<label class="col-md-2 control-label" for="recno">Record No</label>  
						  			<div class="col-md-2">
										<input id="recno" name="recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="trantype">Transaction Type</label>
									<div class="col-md-3">
									  <div class='input-group'>
										<input id="trantype" name="trantype" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  	</div>

						  		<label class="col-md-2 control-label" for="docno">Document No</label>  
						  			<div class="col-md-2">
										<input id="docno" name="docno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="srcdocno">Request RecNo</label>  
						  			<div class="col-md-3" id="srcdocno_parent">
									  	<div class='input-group'>
											<input id="srcdocno" name="srcdocno" type="text" class="form-control input-sm" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
						  			</div>

							</div>

							<hr/>
							
							<div class="form-group">
						  		<label class="col-md-2 control-label" for="sndrcvtype">Receiver Type</label>  
						  			<div class="col-md-3 selectContainer" id="sndrcvtype_parent">
						  				<select id="sndrcvtype" name="sndrcvtype" class="form-control" data-validation="required">
						  				<option value="">Please Select</option>
									      <option value="Department">Department</option>
									      <option value="Supplier">Supplier</option>
									      <option value="Other">Other</option>
									    </select>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="sndrcv">Receiver</label>	  
								<div class="col-md-3" id="sndrcv_parent">
									  <div class='input-group'>
										<input id="sndrcv" name="sndrcv" type="text" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

						  		<label class="col-md-2 control-label" for="amount">Amount</label>  
						  			<div class="col-md-2">
										<input id="amount" name="amount" type="text" class="form-control input-sm" value='0.00' rdonly>
						  			</div>
							</div>

							<div class="form-group">
					    		<label class="col-md-2 control-label" for="trandate">Transaction Date</label>  
							  	<div class="col-md-2">
									<input id="trandate" name="trandate" type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date('Y-m-d');?>" class="form-control input-sm" data-validation="required">
							  	</div>

							  	<label class="col-md-3 control-label" for="recstatus">Status</label>  
							  	<div class="col-md-2">
									<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" rdonly>
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
									<label class="control-label" for="adduser">Entered By</label>  
						  			<input id="adduser" name="adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="adddate">Entered Date</label>
						  			<input id="adddate" name="adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
						    	<div class="col-md-6 minuspad-13">
									<label class="control-label" for="upduser">Last Entered By</label>  
						  			<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="upddate">Last Entered Date</label>
						  			<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
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
						<div class="noti"><ol></ol>
						</div>
					</div>

			</div>
		</div>
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="invTran.js"></script>
	<script src="pdfgen.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/plugins/pdfmake/pdfmake.min.js"></script>
	<script src="../../../../assets/plugins/pdfmake/vfs_fonts.js"></script>
	<!-- <script src="../../../../assets/js/dialogHandler.js"></script> -->

<script>
		
</script>
</body>
</html>