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
					<!--<div style="position:absolute;bottom:0;right:0;">
						<label class="checkbox-inline"><input type="checkbox" value=""><b> Repeat add</b></label>
					</div>-->
				</div>
			 </fieldset> 
		</form>
    	<div class="panel panel-default">
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="prevnext btn-group pull-right">
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
				  <div class="col-md-3">
					<input id="bankcode" name="bankcode" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="bankname">Name</label>  
				  <div class="col-md-8">
				  <input id="bankname" name="bankname" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="address1">Address</label>  
				  <div class="col-md-8">
				  <input id="address1" name="address1" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address2" name="address2" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address3" name="address3" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="statecode">State Code</label>  
				  <div class="col-md-3">
				  <input id="statecode" name="statecode" type="text" class="form-control input-sm" >
				  </div>
				  
				  <label class="col-md-2 control-label" for="postcode">Post Code</label>  
				  <div class="col-md-3">
				  <input id="postcode" name="postcode" type="text" class="form-control input-sm" data-validation="number">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="country">Standard Code</label>  
				  <div class="col-md-3">
				  <input id="country" name="country" type="text" class="form-control input-sm">
				  </div>
				  
				  <label class="col-md-2 control-label" for="contact">Contact Person</label>  
				  <div class="col-md-3">
				  <input id="contact" name="contact" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="telno">Telephone No.</label>  
				  <div class="col-md-3">
				  <input id="telno" name="telno" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				  
				  <label class="col-md-2 control-label" for="clearday">Clearing Days</label>  
				  <div class="col-md-3">
				  <input id="clearday" name="clearday" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="faxno">Fax No.</label>  
				  <div class="col-md-3">
				  <input id="faxno" name="faxno" type="text" class="form-control input-sm">
				  </div>
				  
				  <label class="col-md-2 control-label" for="effectdate">Effective Date</label>  
				  <div class="col-md-3">
				  <input id="effectdate" name="effectdate" type="date" data-date="" data-date-format="DD MMMM YYYY" class="form-control input-sm"
                   data-validation="date" >
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="bankaccount">Bank Account No.</label>  
				  <div class="col-md-3">
				  <input id="bankaccount" name="bankaccount" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				  
				  <label class="col-md-2 control-label" for="pctype">Petty Cash</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="pctype" value='A'>Yes</label>
					<label class="radio-inline"><input type="radio" name="pctype" value='D' checked>No</label>
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="glccode">Cost Center</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="glccode" name="glccode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
				  
				  <label class="col-md-2 control-label" for="glaccno">GL Account</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="glaccno" name="glaccno" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				    </div>
				</div>

				 <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-3">
					<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  </div>
				</div>
				
				<div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-3">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
						  	<div class="col-md-3">
								<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="adddate">Created Date</label>  
						<div class="col-md-3">
						  	<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
						  	<div class="col-md-3">
								<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

						<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

				</div> 

				<div class="form-group">
				<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						  	<div class="col-md-3">
								<input id="ipaddress" name="ipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
					

						<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						  	<div class="col-md-3">
								<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
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
	<script src="bank.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>