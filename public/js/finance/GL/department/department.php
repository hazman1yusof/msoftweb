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
                	<label class="col-md-2 control-label" for="deptcode">Department</label>  
                      <div class="col-md-4">
                      <input id="deptcode" name="deptcode" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-8">
                      <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="costcode">Cost Center</label>  
				  		<div class="col-md-3">
					  		<div class='input-group'>
								<input id="costcode" name="costcode" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					  		<span class="help-block"></span>
				  		</div>

				  	<label class="col-md-2 control-label" for="category">Category Of</label>  
					  	<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="category" value='Hospital' data-validation="required">Hospital</label>
							<label class="radio-inline"><input type="radio" name="category" value='Clinic'>Clinic</label>
							<label class="radio-inline"><input type="radio" name="category" value='Others'>Others</label>
					  </div>
                </div>

                <div class="form-group">
                	<label class="col-md-2 control-label" for="region">Region</label>  
				  		<div class="col-md-3">
					  		<div class='input-group'>
								<input id="region" name="region" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					  		<span class="help-block"></span>
				  		</div>
				  		
				  	<label class="col-md-2 control-label" for="sector">Sector</label>  
				  		<div class="col-md-3">
					  		<div class='input-group'>
								<input id="sector" name="sector" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					  		<span class="help-block"></span>
				  		</div>
                </div>
                
                <div class="form-group">
				  	<label class="col-md-2 control-label" for="chgdept">Charge Dept</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="chgdept" value='1' data-validation="required">Yes</label>
							<label class="radio-inline"><input type="radio" name="chgdept" value='0'>No</label>
				  		</div>

				  	<label class="col-md-2 control-label" for="purdept">Purchase Dept</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="purdept" value='1' data-validation="required">Yes</label>
							<label class="radio-inline"><input type="radio" name="purdept" value='0'>No</label>
				  		</div>

				  	<label class="col-md-2 control-label" for="admdept">Admit Dept</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="admdept" value='1' data-validation="required">Yes</label>
							<label class="radio-inline"><input type="radio" name="admdept" value='0'>No</label>
				  		</div>
				</div> 

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="warddept">Ward Dept</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="warddept" value='1' data-validation="required">Yes</label>
							<label class="radio-inline"><input type="radio" name="warddept" value='0'>No</label>
				  		</div>

				  	<label class="col-md-2 control-label" for="regdept">Register Dept</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="regdept" value='1' data-validation="required">Yes</label>
							<label class="radio-inline"><input type="radio" name="regdept" value='0'>No</label>
				  		</div>

				  	<label class="col-md-2 control-label" for="dispdept">Dispense Dept</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="dispdept" value='1' data-validation="required">Yes</label>
							<label class="radio-inline"><input type="radio" name="dispdept" value='0'>No</label>
				  		</div>
				</div> 

                <div class="form-group">
                	<label class="col-md-2 control-label" for="storedept">Store Dept</label>  
				  		<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="storedept" value='1' data-validation="required">Yes</label>
							<label class="radio-inline"><input type="radio" name="storedept" value='0'>No</label>
				  		</div>

				  	<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  		<div class="col-md-2">
							<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  		</div>
				</div> 

				<div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-2">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
						  	<div class="col-md-2">
								<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="adddate">Created Date</label>  
						<div class="col-md-2">
						  	<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
						  	<div class="col-md-2">
								<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-2">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

						<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-2">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

				</div> 

				<div class="form-group">
				<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						  	<div class="col-md-2">
								<input id="ipaddress" name="ipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
					

						<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						  	<div class="col-md-2">
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
	<script src="department.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!--<script src="../../../../assets/js/dialogHandler.js"></script>-->

<script>
		
</script>
</body>
</html>