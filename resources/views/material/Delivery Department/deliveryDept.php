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
			<div class='col-md-12'>
					
			
				<div class="prevnext btn-group pull-right"></div>
			 	
				<div class="form-group">
				  <label class="col-md-2 control-label" for="deptcode">Delivery Store</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="deptcode" name="deptcode" type="text" maxlength="100" class="form-control input-sm" data-validation="required" frozeOnEdit>
						<a class='input-group-addon btn btn-primary' id="1"><span class='fa fa-ellipsis-h' id="2"></span></a>
					  </div>
					  <span class="help-block"></span>
				</div>
				</div>

			
                <div class="form-group">
				  <label class="col-md-2 control-label" for="addr1">Address</label>  
				  <div class="col-md-8">
				  <input id="addr1" name="addr1" type="text" class="form-control input-sm" >
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="addr2" name="addr2" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="addr3" name="addr3" type="text" class="form-control input-sm">
				  </div>
				</div>

				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="addr4" name="addr4" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="tel">Telephone</label>  
				  <div class="col-md-3">
				  <input id="tel" name="tel" type="text" class="form-control input-sm" >
				 </div>
				
				  <label class="col-md-2 control-label" for="generaltel">General Telephone</label>  
				  <div class="col-md-3">
				  <input id="generaltel" name="generaltel" type="text" class="form-control input-sm">
				 </div>
				 </div>

				 <div class="form-group">
				  <label class="col-md-2 control-label" for="fax">Fax</label>  
				  <div class="col-md-3">
				  <input id="fax" name="fax" type="text" class="form-control input-sm" >
				 </div>
				 
				  <label class="col-md-2 control-label" for="generalfax">General Fax</label>  
				  <div class="col-md-3">
				  <input id="generalfax" name="generalfax" type="text" class="form-control input-sm" >
				 </div>
				 </div>

				  <div class="form-group">
					  <label class="col-md-2 control-label" for="contactper">Contact Person</label>  
					  <div class="col-md-3">
					  <input id="contactper" name="contactper" type="text" class="form-control input-sm">
					 </div>
				
				  	<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				 	 <div class="col-md-3">
					<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  </div>
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
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						<div class="col-md-3">
						  	<input id="ipaddress" name="ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
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
	<script src="deliveryDeptScript.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>