<?php 
	include_once('../../../../header.php'); 
?>
<body>
	 
	<!-------------------------------- table ---------------------->
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
	<!-------------------------------- table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%;' id='formdata'>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="groupid">Group ID</label>  
				  <div class="col-md-4">
				  	<input id="groupid" name="groupid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>
                </div>


				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-10">
				  	<input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
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
	<script src="mgroups.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>