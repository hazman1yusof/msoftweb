<?php 
	include_once('../../../../header.php'); 
?>
<body style="display:none">
	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
			<form class="form-horizontal" id="getItemCode">
				<fieldset>
				
				<div class="col-md-6 form-group">
				<label class="col-md-3 control-label" for="itemcode">Item Code</label>  
				<div class="col-md-6">
			    <div class='input-group'>
	<input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
				</fieldset>
			</form>
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
    </div>
	<!-------------------------------- End Search + table ------------------>
	

		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="prevnext btn-group pull-right">
				</div>

				<input id="itemcode" name="itemcode" type="hidden">

				<div class="form-group">
				  <label class="col-md-2 control-label" for="deptcode">Dept. Code</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="uomcode">UOM Code</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                  
                   <div class="form-group">
				 <label class="col-md-2 control-label" for="stocktxntype">Transaction Type</label>  
				  <div class="col-md-10">
				    <label class="radio-inline"><input type="radio" name="stocktxntype" value='Transfer'>TRANSFER</label>
                    <label class="radio-inline"><input type="radio" name="stocktxntype" value='Issue'>ISSUE</label>				
                </div>
				</div>
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="disptype">Dispensing Type</label>  
				   <div class="col-md-10">
				    <label class="radio-inline"><input type="radio" name="disptype" id="TRDS" value='TR Item'>DS (TR Item)</label>
                    <label class="radio-inline"><input type="radio" name="disptype" id="ISDS1" value='IS Item'>DS (IS Item)</label>				
                </div>
				</div>
                  
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="rackno">Rack No</label>  
				  <div class="col-md-4">
				  <input id="rackno" name="rackno" type="text" maxlength="30" class="form-control input-sm" data-validation="required">
				 </div>
				
				  <label class="col-md-2 control-label" for="bincode">Bin Code</label>  
				  <div class="col-md-4">
				  <input id="bincode" name="bincode" type="text" maxlength="30" class="form-control input-sm" data-validation="required">
				</div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="minqty">Maximum Stock Qty</label>  
                  <div class="col-md-4">
				  <input id="minqty" name="minqty" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" data-validation="required">
				  </div>
                 
				  <label class="col-md-2 control-label" for="maxqty">Minimum Stock Qty</label>  
                  <div class="col-md-4">
				  <input id="maxqty" name="maxqty" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" data-validation="required">
				  </div>
                  </div>
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="reordlevel">Reorder Level</label>  
                  <div class="col-md-4">
				  <input id="reordlevel" name="reordlevel" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" data-validation="required">
				  </div>
                 
				  <label class="col-md-2 control-label" for="reordqty">Reorder Quantity</label>  
                  <div class="col-md-4">
				  <input id="reordqty" name="reordqty" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0">
				  </div>
                  </div>

                  <div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-3">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="upduser">Last Entered</label>  
						<div class="col-md-3">
							<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
				</div>  
			</form>
		</div>

	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="stockLoc3.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!--<script src="../../../../assets/js/dialogHandler.js"></script>-->

<script>
		
</script>
</body>
</html>