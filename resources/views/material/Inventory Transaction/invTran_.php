<?php 
	include_once('../../../../header.php'); 
?>
<body style="display:none">

	<input id="x" name="x" type="hidden" value="<?php echo $_SESSION['deptcode'];?>">

	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>

			<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

			<!--<div class="ScolClass">
						<div name='Scol'>Search By : <select id='Scol'></select> </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div> -->

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
					  <div class="col-md-2">
					  	<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' class="form-control input-sm"></select>
		              </div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label> 
					  		<input id="searchText" name="searchText" type="text" class="form-control input-sm" autocomplete="off"/>
					  		<!--<label class="control-label"></label>  
								<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">-->
						</div>
		             </div>
				</div>

				<br><button type="button" id='postedBut' class='btn btn-info pull-right' style='margin: 0.5%'>Post</button>

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
					  		<label class="control-label" for="trandept">Tran Dept</label> 
								<select id='trandept' class="form-control input-sm"></select>
						</div>

			 </fieldset> 
		</form>


		  	<div class="panel panel-default">
		    	<div class="panel-heading">Inventory Transaction Header</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		  </div>


		   	<div class="panel panel-default">
		    	<div class="panel-heading">Inventory Transaction Detail</div>
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
				<div class="panel-heading">Inventory DataEntry Header</div>
					<div class="panel-body">
						<form class='form-horizontal' style='width:99%' id='formdata'>

							<div class="prevnext btn-group pull-right">
							</div>

							<input id="source" name="source" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="txndept">Transaction Department</label>	  <div class="col-md-2">
									  <div class='input-group'>
										<input id="txndept" name="txndept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

						  		<label class="col-md-2 control-label" for="srcdocno">Request RecNo</label>  
						  			<div class="col-md-2">
										<input id="srcdocno" name="srcdocno" type="text" maxlength="30" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value='0'>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="trantype">Transaction Type</label>	  <div class="col-md-2">
									  <div class='input-group'>
										<input id="trantype" name="trantype" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

						  		<label class="col-md-2 control-label" for="docno">Document No</label>  
						  			<div class="col-md-2">
										<input id="docno" name="docno" type="text" maxlength="11" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" frozeOnEdit hideOne>
						  			</div>
							</div>

							<div class="form-group">
						  		<label class="col-md-2 control-label" for="sndrcvtype">Receiver Type</label>  
						  			<div class="col-md-2 selectContainer" id="sndrcvtype_parent">
						  				<!--<label class="radio-inline"><input type="radio" name="sndrcvtype" value='Department' >Department</label>
						  				<label class="radio-inline"><input type="radio" name="Supplier" value='0' >Supplier</label>
						  				<label class="radio-inline"><input type="radio" name="Other" value='0' >Other</label>-->

						  				<select id="sndrcvtype" name="sndrcvtype" class="form-control" data-validation="required">
						  				<option value="">Please Select</option>
									      <option value="Department">Department</option>
									      <option value="Supplier">Supplier</option>
									      <option value="Other">Other</option>
									    </select>
									    <!--<select id="sndrcvtype" name="sndrcvtype" class="selectpicker" data-size="4" multiple data-max-options="1">
									      <option value="Department">Department</option>
									      <option value="Supplier">Supplier</option>
									      <option value="Other">Other</option>
									    </select>-->
						  			</div>

						  		<label class="col-md-2 control-label" for="recno">Record No</label>  
						  			<div class="col-md-2">
										<input id="recno" name="recno" type="text" maxlength="11" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" frozeOnEdit hideOne>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="sndrcv">Receiver</label>	  
								<div class="col-md-2" id="sndrcv_parent">
									  <div class='input-group'>
										<input id="sndrcv" name="sndrcv" type="text" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

						  		<label class="col-md-2 control-label" for="amount">Amount</label>  
						  			<div class="col-md-2">
										<input id="amount" name="amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.0000" value='0.00' rdonly>
						  			</div>
							</div>

							<div class="form-group">
					    	<label class="col-md-2 control-label" for="trandate">Transaction Date</label>  
							  	<div class="col-md-2">
									<input id="trandate" name="trandate" type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date('Y-m-d');?>" class="form-control input-sm" data-validation="required">
							  	</div>

							<!-- <label class="col-md-2 control-label" for="recstatus">Status</label>  
							  	<div class="col-md-2">
										<input id="recstatus" name="recstatus" maxlength="10" class="form-control input-sm" frozeOnEdit hideOne>
				 				</div>-->
					    	</div>

					    	<div class="form-group">
								<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
									<div class="col-md-2">
										<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
									</div>

								<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
						  			<div class="col-md-3">
										<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  			</div>
							</div>
					</form>
				</div>
			</div>

			<div class='panel panel-info'>
				<div class="panel-heading">Inventory DataEntry Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<div id="jqGrid2_c" class='col-md-12' style="padding:0 0 15px 0">
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
	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="invTran.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!-- <script src="../../../../assets/js/dialogHandler.js"></script> -->

<script>
		
</script>
</body>
</html>