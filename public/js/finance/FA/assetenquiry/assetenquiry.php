<?php 
	include_once('../../../../header.php'); 
?>
<body>

	 
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

		<button type="button" id='histbut' class='btn btn-info pull-right' style='margin: 0.2%'>History</button>
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

    <div id="msgBox" title="Particulars of Asset Movement" style="display:none">
    <ul>
			<b>ASSET NO  : </b><span name='assetno' ></span> <br><br>
			<b>DESCRIPTION: </b><span name='description' ></span>
			
		</ul>	

		<div id='gridhist_c' style="padding:15px 0 0 0">
            <table id="gridhist" class="table table-striped"></table>
            <div id="gridhistpager"></div>
        </div>
	</div>

	<!-------------------------------- End Search + table ------------------>
	
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
              
				<div class="prevnext btn-group pull-right"></div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="assetcode">Category</label>
					<div class="col-md-2">
						
							<input id="assetcode" name="assetcode" type="text" class="form-control input-sm" data-validation="required"  frozeOnEdit>
							
						
						
					</div>
				<label class="col-md-2 control-label" for="assettype">Type</label>  
				  	<div class="col-md-2">
				  		<input id="assettype" name="assettype" type="text" maxlength="100" class="form-control input-sm" rdonly frozeOnEdit>
				  	</div>
				  	

				  	<label class="col-md-2 control-label" for="assetno">NO</label>  
				  	<div class="col-md-2">
				  		<input id="assetno" name="assetno" type="text" maxlength="100" class="form-control input-sm" rdonly frozeOnEdit>
				  	</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="description">Description</label>  
					<div class="col-md-3">
                    	<textarea class="form-control input-sm" name="description" rows="3" cols="55" maxlength="100" id="description"></textarea>
                    </div>
				
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="deptcode">Department</label>
					<div class="col-md-2">
						
							<input id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
							
						
						
					</div>
				<label class="col-md-2 control-label" for="loccode">Location</label>
					<div class="col-md-2">
						
							<input id="loccode" name="loccode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
							
					
						
					</div>
			</div>
			<hr>

			

			<div class="form-group">
				<label class="col-md-2 control-label" for="suppcode">Supplier</label>
					<div class="col-md-2">
					
							<input id="suppcode" name="suppcode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
						
						
					</div>
				<label class="col-md-2 control-label" for="delordno">Delivery Order No.</label>
					<div class="col-md-2">
					
							<input id="delordno" name="delordno" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
							
						
						
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="invno">Invoice No.</label>
					<div class="col-md-2">
						<input id="invno" name="invno" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
					</div>
				<label class="col-md-2 control-label" for="delorddate">Delivery Order Date</label>
					<div class="col-md-2">
						<input id="delorddate" name="delorddate" type="text" class="form-control input-sm" 	data-validation="required" frozeOnEdit>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="invdate">Invoice Date</label>  
					<div class="col-md-2">
						<input id="invdate" name="invdate" type="text" class="form-control input-sm" 	data-validation="required" placeholder="YYYY-MM-DD" frozeOnEdit>
					</div>
				
</div>
<!---<div class="form-group">
				<label class="col-md-2 control-label" for="itemcode">Item Code</label>
					<div class="col-md-2">
						
							<input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
							
						
						
					</div>
                   
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="description"></label>  
					<div class="col-md-5">
                    	<textarea class="form-control input-sm" name="description" rows="1" cols="55" maxlength="100" id="description"></textarea>
                    </div>
				
			</div> ---->
		

			<div class="form-group">
				<label class="col-md-2 control-label" for="purordno">Purchase No.</label>
					<div class="col-md-2">
						<input id="purordno" type="text" name="purordno" class="form-control input-sm" data-validation="required" frozeOnEdit>
					</div>
				<label class="col-md-2 control-label" for="purdate">Purchase Date</label>
					<div class="col-md-2">
						<input id="purdate" type="text" name="purdate" class="form-control input-sm" data-validation="required" frozeOnEdit>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="purprice">Price</label>  
					<div class="col-md-2">
						<input id="purprice" name="purprice" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit>  
				 	</div>
				<label class="col-md-2 control-label" for="qty">Quantity</label>  
					<div class="col-md-2">
						<input id="qty" name="qty" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit> 
				 	</div>
			</div>
<hr>

			

			<div class="form-group">
				<label class="col-md-2 control-label" for="serialno">Serial No</label>  
					<div class="col-md-2">
						<input id="serialno" type="text" name="serialno" maxlength="12" class="form-control input-sm" >
					</div>
				<label class="col-md-2 control-label" for="lotno">Lot No</label>  
					<div class="col-md-2">
						<input id="lotno" type="text" name="lotno" maxlength="12" class="form-control input-sm" >
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="casisno">Casis No</label>  
					<div class="col-md-2">
						<input id="casisno" name="casisno" type="text" class="form-control input-sm" 	data-validation="required">
					</div>
			

			<div class="form-group">
				<label class="col-md-2 control-label" for="engineno">Engine No</label>  
					<div class="col-md-2">
						<input id="engineno" name="engineno" maxlength="12" class="form-control input-sm" data-sanitize="required" >  
				 	</div>
			</div>

			

			
			<hr>

			

			<div class="form-group">
				<label class="col-md-2 control-label" for="method">Method</label>  
					<div class="col-md-2">
						<input id="method" type="text" name="method" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
					</div>
				<label class="col-md-2 control-label" for="rvalue">Residual Value</label>  
					<div class="col-md-2">
						<input id="rvalue" type="text" name="rvalue" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="statdate">Start Date</label>  
					<div class="col-md-2">
						<input id="statdate" name="statdate" type="text" class="form-control input-sm" 	data-validation="required" frozeOnEdit>
					</div>

					<label class="col-md-2 control-label" for="rate">Rate (%p.a)</label>  
					<div class="col-md-2">
						<input id="rate" type="text" name="rate" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="origcost">Cost</label>  
					<div class="col-md-2">
						<input id="origcost" name="origcost" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" frozeOnEdit>  
				 	</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="lstytddep">Accum.(Prev Year)</label>  
					<div class="col-md-2">
						<input id="lstytddep" name="lstytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit>
					</div>
				<label class="col-md-2 control-label" for="recstatus">Status</label>
					<div class="col-md-2">
						<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Activated</label>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="cuytddep">Accum.(Y-T-D)</label>  
					<div class="col-md-2">
						<input id="cuytddep" name="cuytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit>
					</div>
				<label class="col-md-2 control-label" for="trantype">Tran Type</label>
					<div class="col-md-2">
						<input id="trantype" type="text" name="trantype" class="form-control input-sm" value="ADDITIONAL" rdonly frozeOnEdit>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="nbv">N-B-V</label>
					<div class="col-md-2">
						<input id="nbv" type="text" name="nbv" maxlength="12" class="form-control input-sm" rdonly>
					</div>
					
				<label class="col-md-2 control-label" for="trandate">Post Date</label>  
					<div class="col-md-2">
						<input id="trandate" name="trandate" type="text" class="form-control input-sm" 	data-validation="required" frozeOnEdit>
					</div>
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
	<script src="assetenquiry.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
    <script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>