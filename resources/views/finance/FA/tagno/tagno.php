<?php 
	include_once('../../../../header.php'); 
?>

<body style="display:none">

	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div> 
						
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
					
				</div>
			 </fieldset> 
		</form>
		<button type="button" id='postedBut' class='btn btn-info pull-right' style='margin: 0.2%'>Post</button>
		<br><br><br>
		
    	<div class="panel panel-default">
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

    </div>
	<div>
	


	<!-------------------------------- End Search + table ------------------>
		
	<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
              
				<div class="prevnext btn-group pull-right"></div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="assetcode">Category</label>
					<div class="col-md-3">
						
							<input id="assetcode" name="assetcode" type="text" class="form-control input-sm" data-validation="required">
							
						
						
					</div>
				<label class="col-md-2 control-label" for="assettype">Type</label>  
				  	<div class="col-md-3">
				  		<input id="assettype" name="assettype" type="text" maxlength="100" class="form-control input-sm" rdonly>
				  	</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="deptcode">Department</label>
					<div class="col-md-3">
						
							<input id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required">
							
						
						
					</div>
				<label class="col-md-2 control-label" for="loccode">Location</label>
					<div class="col-md-3">
						
							<input id="loccode" name="loccode" type="text" class="form-control input-sm" data-validation="required">
							
					
						
					</div>
			</div>
			<hr>

			<div class="form-group">
				<label class="col-md-2 control-label" for="regtype">Register Type</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="regtype" value='P'>Purchase Order</label>
					<label class="radio-inline"><input type="radio" name="regtype" value='D'>Direct</label>
				  </div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="suppcode">Supplier</label>
					<div class="col-md-3">
					
							<input id="suppcode" name="suppcode" type="text" class="form-control input-sm" data-validation="required">
						
						
					</div>
				<label class="col-md-2 control-label" for="delordno">Delivery Order No.</label>
					<div class="col-md-3">
					
							<input id="delordno" name="delordno" type="text" class="form-control input-sm" data-validation="required">
							
						
						
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="invno">Invoice No.</label>
					<div class="col-md-3">
						<input id="invno" name="invno" type="text" class="form-control input-sm" data-validation="required">
					</div>
				<label class="col-md-2 control-label" for="delorddate">Delivery Order Date</label>
					<div class="col-md-3">
						<input id="delorddate" name="delorddate" type="text" class="form-control input-sm" 	data-validation="required">
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="invdate">Invoice Date</label>  
					<div class="col-md-3">
						<input id="invdate" name="invdate" type="text" class="form-control input-sm" 	data-validation="required" placeholder="YYYY-MM-DD">
					</div>
				<label class="col-md-2 control-label" for="docno">GRN No</label>  
					<div class="col-md-3">
						<input id="docno" name="docno" type="text" class="form-control input-sm" 	data-validation="required">
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="itemcode">Item Code</label>
					<div class="col-md-3">
						
							<input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required">
							
						
						
					</div>
                    <div class="col-md-5">
                    	<input id="description" name="description" type="text" maxlength="100" class="form-control input-sm">
                    </div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="purordno">Purchase No.</label>
					<div class="col-md-3">
						<input id="purordno" type="text" name="purordno" class="form-control input-sm" data-validation="required">
					</div>
				<label class="col-md-2 control-label" for="purdate">Purchase Date</label>
					<div class="col-md-3">
						<input id="purdate" type="text" name="purdate" class="form-control input-sm" data-validation="required">
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="purprice">Price</label>  
					<div class="col-md-3">
						<input id="purprice" name="purprice" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required">  
				 	</div>
				<label class="col-md-2 control-label" for="qty">Quantity</label>  
					<div class="col-md-3">
						<input id="qty" name="qty" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required"> 
				 	</div>
			</div>

			<hr>

			<div class="form-group">
				<label class="col-md-2 control-label" for="individualtag">Individual Tagging</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="individualtag" value='Y' checked>Yes</label>
					<label class="radio-inline"><input type="radio" name="individualtag" value='N'>No</label>
				  </div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="method">Method</label>  
					<div class="col-md-3">
						<input id="method" type="text" name="method" maxlength="12" class="form-control input-sm" rdonly>
					</div>
				<label class="col-md-2 control-label" for="rvalue">Residual Value</label>  
					<div class="col-md-3">
						<input id="rvalue" type="text" name="rvalue" maxlength="12" class="form-control input-sm" rdonly>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="statdate">Start Date</label>  
					<div class="col-md-3">
						<input id="statdate" name="statdate" type="text" class="form-control input-sm" 	data-validation="required">
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="origcost">Cost</label>  
					<div class="col-md-3">
						<input id="origcost" name="origcost" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00">  
				 	</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="lstytddep">Accum.(Prev Year)</label>  
					<div class="col-md-3">
						<input id="lstytddep" name="lstytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required">
					</div>
				<label class="col-md-2 control-label" for="recstatus">Status</label>
					<div class="col-md-4">
						<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Activated</label>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="cuytddep">Accum.(Y-T-D)</label>  
					<div class="col-md-3">
						<input id="cuytddep" name="cuytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required">
					</div>
				<label class="col-md-2 control-label" for="trantype">Tran Type</label>
					<div class="col-md-3">
						<input id="trantype" type="text" name="trantype" class="form-control input-sm" value="ADDITIONAL" rdonly>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="nbv">N-B-V</label>
					<div class="col-md-3">
						<input id="nbv" type="text" name="nbv" maxlength="12" class="form-control input-sm" rdonly>
					</div>
				<label class="col-md-2 control-label" for="trandate">Post Date</label>  
					<div class="col-md-3">
						<input id="trandate" name="trandate" type="text" class="form-control input-sm" 	data-validation="required">
					</div>
			</div>

			<hr>
		</form>
	</div>
	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="tagno.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>