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
		 
            <button type="button" id='cancelBut' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Cancel</button>
		<button type="button" id='postedBut' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Post</button>
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

						<div class="form-group" style="position: relative">
						  	<label class="col-md-2 control-label" for="auditno">Audit No</label>  
						  		<div class="col-md-2"> <!--- value="<?php// echo "auditno";?>" -->
						  			<input id="auditno" name="auditno" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>

						  	<label class="col-md-3 control-label" for="pvno">PV No</label>  
						  		<div class="col-md-3">
									<input id="pvno" name="pvno" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
						  	<div id="recstatus"></div>
						</div>

						<div class="form-group">
						  	<label class="col-md-2 control-label" for="actdate">Payment Date</label>  
						  		<div class="col-md-3">
									<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
						  		</div>

						  	<label class="col-md-2 control-label" for="paymode">Payment Mode</label>  
						  		<div class="col-md-3">
							 		<div class='input-group'>
										<input id="paymode" name="paymode" type="text" class="form-control input-sm" data-validation="required" >
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  		</div>
							 		<span class="help-block"></span>
		                      	</div>
					    </div>

					    <div class="form-group">
					    	<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
						  		<div class="col-md-3" id="bankcode_parent">
							 		<div class='input-group'>
										<input id="bankcode" name="bankcode" type="text" class="form-control input-sm" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  		</div>
							 		<span id='bc' class="help-block"></span>
		                      	</div>

		                    <label class="col-md-2 control-label" for="cheqno">Cheque No</label>  
						  		<div class="col-md-3" id="cheqno_parent">
							 		<div class='input-group'>
										<input id="cheqno" name="cheqno" type="text" class="form-control input-sm">
											<a class='input-group-addon btn btn-primary' id="cheqno_a"><span class='fa fa-ellipsis-h' ></span></a>
							  		</div>
							 		<span id='cn' class="help-block"></span>
		                      	</div>
					    </div>

					    <div class="form-group">
					    	<label class="col-md-2 control-label" for="cheqdate">Cheque Date</label>  
							  	<div class="col-md-3">
									<input id="cheqdate" name="cheqdate" type="date"  maxlength="12" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
							  	</div>

							 <label class="col-md-2 control-label" for="amount">Amount</label>  
							  	<div class="col-md-2">
										<input id="amount" name="amount" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" rdonly>  <!--data-validation-allowing="float" -->
				 				</div>
					    </div>

					    <div class="form-group">
					    	<label class="col-md-2 control-label" for="payto">Pay To</label>  
						  		<div class="col-md-3">
							 		<div class='input-group'>
										<input id="payto" name="payto" type="text" class="form-control input-sm" data-validation="required"><!---->
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  		</div>
							 		<span class="help-block"></span>
		                      	</div>
		                      	
					        
							<label class="col-md-2 control-label" for="TaxClaimable">GST</label>  
							  <div class="col-md-3">
								<label class="radio-inline"><input type="radio" data-validation="required" name="TaxClaimable" value='Claimable'>Claimable</label><br>
								<label class="radio-inline"><input type="radio" data-validation="required" name="TaxClaimable" value='Non-Claimable'>Non-Claimable</label>
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
	<script src="DirectPayment.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!--<script src="../../../../assets/js/dialogHandler.js"></script>-->

<script>
		
</script>
</body>
</html>