<?php 
	include_once('../../../../header.php'); 
?>
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
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
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

						<div class="form-group">
						  	<label class="col-md-2 control-label" for="auditno">Audit No</label>  
						  		<div class="col-md-2"> <!--- value="<?php// echo "auditno";?>" -->
						  			<input id="auditno" name="auditno" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>

						  	<label class="col-md-3 control-label" for="pvno">PV No</label>  
						  		<div class="col-md-3">
									<input id="pvno" name="pvno" type="text" maxlength="30" class="form-control input-sm" data-validation="required">
						  		</div>
						</div>

						<div class="form-group">
						  	<label class="col-md-2 control-label" for="actdate">Payment Date</label>  
						  		<div class="col-md-2">
									<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required">
						  		</div>

						  	<label class="col-md-3 control-label" for="paymode">Payment Mode</label>  
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
						  		<div class="col-md-3">
							 		<div class='input-group'>
										<input id="bankcode" name="bankcode" type="text" class="form-control input-sm" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  		</div>
							 		<span class="help-block"></span>
		                      	</div>

		                    <label class="col-md-2 control-label" for="cheqno">Cheque No</label>  
						  		<div class="col-md-3">
							 		<div class='input-group'>
										<input id="cheqno" name="cheqno" type="text" class="form-control input-sm" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  		</div>
							 		<span class="help-block"></span>
		                      	</div>
					    </div>

					    <div class="form-group">
					    	<label class="col-md-2 control-label" for="cheqdate">Date</label>  
							  	<div class="col-md-2">
									<input id="cheqdate" name="cheqdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required">
							  	</div>

							 <label class="col-md-3 control-label" for="amount">Amount</label>  
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
		                      	<div class="col-md-5">
		                      	</div>
					    </div>

					    <div class="form-group">
					    	<label class="col-md-2 control-label" for="remarks">Remarks</label> 
					    	<div class="col-md-8"> 
					    	<textarea wrap="hard" class="col-md" rows="2" cols="50" id="remarks" name="remarks" type="text" maxlength="100">
					    	</textarea>
					    	</div>
					    </div>
					    <button type="button" id="button_D" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" style="float: right"><span class="ui-button-text">Detail</span></button>
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
					         <button type="button" id="button_H" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" style="float: right"><span class="ui-button-text">Header</span></button>
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
	<script src="DirectPayment3.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!--<script src="../../../../assets/js/dialogHandler.js"></script>-->

<script>
		
</script>
</body>
</html>