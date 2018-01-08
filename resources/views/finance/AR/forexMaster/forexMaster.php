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
    <!--	<div class='col-md-12' style="padding:0 0 0 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>-->
        <div class="panel panel-default">
		    	<div class="panel-heading">Forex Code</div>
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

				<div class="form-group">
				  <label class="col-md-2 control-label" for="forexcode">Forex Code</label>  
				  <div class="col-md-4">
				  <input id="forexcode" name="forexcode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>
                </div>
				
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-8">
				  <input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                </div>
                
				<div class="form-group">
					<label class="col-md-2 control-label" for="costcode">Cost Code</label>  
					<div class="col-md-3">
					  <div class='input-group'>
						<input id="costcode" name="costcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
					
					<label class="col-md-2 control-label" for="glaccount">GL Account</label>  
					<div class="col-md-3">
					  <div class='input-group'>
						<input id="glaccount" name="glaccount" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
				  
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="adduser">Add User</label>  
				  <div class="col-md-3">
				  <input id="adduser" name="adduser" type="text" class="form-control input-sm" rdonly>
				  </div>
				  
				  <label class="col-md-2 control-label" for="adddate">Add Date</label>  
					<div class="col-md-3">
					<input id="adddate" name="adddate" type="text" class="form-control input-sm" rdonly>
				  </div>
				</div>

			</form>
		</div>


		<!--------------- forex ----------- Search + table ---------------------->
		<div class='row'>
			<form id="searchForm2" class="formclass" style='width:99%'>
				<fieldset>
					<div class="ScolClass">
							<div name='Scol'>Search By : </div>
					</div>
					<div class="StextClass">
						<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
					</div>
				 </fieldset> 
			</form>
	    <!--	<div class='col-md-12' style="padding:0 0 15px 0">
	            <table id="jqGrid2" class="table table-striped"></table>
	            <div id="jqGridPager2"></div>
	        </div>-->
	        <div class="panel panel-default">
		    	<div class="panel-heading">Forex Rate</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid2" class="table table-striped"></table>
            					<div id="jqGridPager2"></div>
        				</div>
		    		</div>
		</div>

	    </div>
		<!--------------- forex ----------- End Search + table ------------------>
			
			<div id="dialogForm2" title="Add Form" >
				<form class='form-horizontal' style='width:99%' id='formdata2'>

					<input id="idno" name="idno" type="hidden">

					<div class="form-group">
					  <label class="col-md-2 control-label" for="forexcode">Forex Code</label>  
					  <div class="col-md-4">
					  <input id="forexcode" name="forexcode" type="text" class="form-control input-sm" data-validation="required" rdonly>
					  </div>
	                </div>
					
					
					<div class="form-group">
					  <label class="col-md-2 control-label" for="rate">Rate</label>  
					  <div class="col-md-4">
					  <input id="rate" name="rate" type="text" class="form-control input-sm" data-validation="required">
					  </div>


					  <label class="col-md-2 control-label" for="effdate">Effective Date</label>  
					  <div class="col-md-4">
					  <input id="effdate" name="effdate" type="date" class="form-control input-sm" data-validation="required">
					  </div>
	                </div>

					<div class="form-group">
					  <label class="col-md-2 control-label" for="adduser">Add User</label>  
					  <div class="col-md-4">
					  <input id="adduser" name="adduser" type="text" class="form-control input-sm" rdonly>
					  </div>
					  
					  <label class="col-md-2 control-label" for="adddate">Add Date</label>  
						<div class="col-md-4">
						<input id="adddate" name="adddate" type="text" class="form-control input-sm" rdonly>
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
	<script src="forexMaster.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>