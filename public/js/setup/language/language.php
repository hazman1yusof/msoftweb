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
				  <label class="col-md-3 control-label" for="Code">Language Code</label>  
                      <div class="col-md-4">
                      <input id="Code" name="Code" type="text" maxlength="10" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-3 control-label" for="Description">Description</label>  
                      <div class="col-md-8">
                      <input id="Description" name="Description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-3 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>
				  </div>
				</div> 

				<div class="form-group">
					<label class="col-md-3 control-label" for="adduser">Created By</label>  
						<div class="col-md-3">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
						<div class="col-md-3">
							<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
				</div> 

				<div class="form-group">
					<label class="col-md-3 control-label" for="lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" rdonly >
						</div>

					<label class="col-md-3 control-label" for="lastipaddress">IP Address</label>  
					  	<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" rdonly >
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
	<script src="language.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>