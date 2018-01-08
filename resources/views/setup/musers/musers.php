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
				  <label class="col-md-2 control-label" for="username">Username</label>  
				  <div class="col-md-4">
				  	<input id="username" name="username" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>
                </div>


				<div class="form-group">
				  <label class="col-md-2 control-label" for="name">Name</label>  
				  <div class="col-md-10">
				  	<input id="name" name="name" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                </div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="password">Password</label>  
				  <div class="col-md-4">
				  <input id="password" name="password" type="text" class="form-control input-sm" data-validation="required">
				  </div>


				  <label class="col-md-2 control-label" for="groupid">Group</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="groupid" name="groupid" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
                </div>

                <div class="form-group">


				  <label class="col-md-2 control-label" for="programmenu">Menu</label>  
				  <div class="col-md-4">
				  <input id="programmenu" name="programmenu" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  </div>


				  <label class="col-md-2 control-label" for="deptcode">Department</label>  
				  <div class="col-md-4">
				  <input id="url" name="deptcode" type="deptcode" class="form-control input-sm" data-validation="required">
				  </div>
                
			</form>
		</div>
	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="musers.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>