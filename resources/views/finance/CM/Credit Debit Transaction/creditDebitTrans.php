<?php 
	include_once('../../../../header.php'); 
?>

<style>
	#recstatus{
		position: absolute;
		right: 0;
		top: 0;
		font-weight: 600;
		font-size: medium;
		color: #d78a88;
	}
</style>


<body>
	 
	<!--***************************** Search + table ******************-->
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
	<!--	<button id='cancelBut' class='btn btn-info pull-right' style='margin: 0.2%'>Cancel</button>
		<button id='postedBut' class='btn btn-info pull-right' style='margin: 0.2%'>Post</button>
		<br><br><br>-->

		<div class='col-md-12' style="padding:0 0 15px 0;">
			<div class="form-group"> 
			  <div class="col-md-4">
			  	<label class="control-label" for="adjustment">Transaction</label>  
			  	<select id="adjustment" name="adjustment" class="form-control input-sm">
			      <option selected value="CA">Credit</option>
			      <option value="DA">Debit</option>
			    </select>
              </div>
            </div>

            <button type="button" id='cancelBut' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Cancel</button>
		<button type="button" id='postedBut' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Post</button>
        </div>

        
    	<div class="panel panel-default">
		    	
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		
		</div>

    </div>
	<!-- ***************End Search + table ********************* -->



	<div id="dialogForm" title="Add Form" >

		<div class='panel panel-info'>
			<div class="panel-heading">Header</div>
				<div class="panel-body">

					<form class='form-horizontal' style='width:99%' id='formdata'>

						<div class="prevnext btn-group pull-right">
						</div>

						<input id="source" name="source" type="hidden">
						<input id="trantype" name="trantype" type="hidden">
						<input id="pvno" name="pvno" type="hidden">

						<div class="form-group" style="position: relative">
						  	<label class="col-md-2 control-label" for="auditno">Audit No</label>  
						  		<div class="col-md-3"> <!--- value="<?php// echo "auditno";?>" -->
						  			<input id="auditno" name="auditno" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>

						  		<label class="col-md-3 control-label" for="pvno" type="hidden"></label>  
						  		<div class="col-md-3">
									<input id="pvno" name="pvno" type="hidden" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>

						  	<div id="recstatus"></div>
						</div>

						<div class="form-group">
						  	<label class="col-md-2 control-label" for="actdate">Payment Date</label>  
						  		<div class="col-md-3">
									<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>">
						  		</div>

					    
					    	<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
						  		<div class="col-md-3">
							 		<div class='input-group'>
										<input id="bankcode" name="bankcode" type="text" class="form-control input-sm" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  		</div>
							 		<span class="help-block"></span>
		                      	</div>
		                </div>

		                <div class="form-group">
							 <label class="col-md-2 control-label" for="amount">Amount</label>  
							  	<div class="col-md-3">
										<input id="amount" name="amount" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" rdonly>  <!--data-validation-allowing="float" -->
				 				</div>

				 				<label class="col-md-2 control-label" for="TaxClaimable">GST</label>  
							  <div class="col-md-3">
								<label class="radio-inline"><input type="radio" name="TaxClaimable" data-validation="required" value='Claimable'>Claimable</label><br>
								<label class="radio-inline"><input type="radio" name="TaxClaimable" data-validation="required" value='Non-Claimable'>Non-Claimable</label>
							  </div>
				 		</div>
					    <div class="form-group">

					    	<label class="col-md-2 control-label" for="refsource">Reference</label>  
								<div class="col-md-8">
									<input id="refsource" class="form-control input-sm" name="refsource" rows="1" cols="55" maxlength="100" id="remarks">
									</div>
						</div>

					    <div class="form-group">
					    	<label class="col-md-2 control-label" for="remarks">Remarks</label> 
					    		<div class="col-md-8"> 
					    		<textarea class="form-control input-sm" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" ></textarea>
					    		</div>
					    	
					    </div>
					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-horizontal' style='width:99%'>
					    	<div id="jqGrid2_c" class='col-md-12' style="padding:0 0 15px 0">
					            <table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
					        </div>
						</form>
					</div>
				</div>
		</div>
		
		

	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level-->
	<script src="creditDebitTransScript.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!--<script src="../../../../assets/js/dialogHandler.js"></script>-->

<script>
		
</script>
</body>
</html>