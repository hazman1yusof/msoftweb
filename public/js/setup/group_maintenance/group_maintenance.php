<?php 
	include_once('../../../../header.php'); 
?>
<body>
	 
	<!-------------------------------- table ---------------------->
	<div class="panel panel-default" style="margin: 10px; padding: 5px">
		<ul class="nav nav-pills ">
			<li class="active"><a data-toggle="pill" href="#grp_main"><i class="fa fa-users"></i> Group Maintenance</a></li>
			<li class=""><a data-toggle="pill" href="#grp_access" ><i class="fa fa-lock"></i> Set Security</a></li>
		</ul>
	</div>

	<div class="tab-content">

		<div id="grp_main" class="tab-pane fade in active">

			<div class='row'>
		    	<div id="jqGrid_grpmaintenance_c" class='col-md-12' style="padding:0 0 15px 0">
		            <table id="jqGrid_grpmaintenance" class="table table-striped"></table>
		            <div id="jqGridPager_grpmaintenance"></div>
		        </div>
		    </div>

		</div>

		<div id="grp_access" class="tab-pane fade">

			<div class="well" menunavigation style="margin: 10px;"><h4>Menu Navigation:</h4>
				<div id='btngroup' class="btn-group btn-group-justified" role="group" aria-label="...">
					<div class="btn-group" role="group">
						<button type="button" class="btn btn-primary" programid='main'>Main</button>
					</div>
				</div>
			</div>

			<div class='row'>
		    	<div id="jqGrid_grpaccess_c" class='col-md-12' style="padding:0 0 15px 0">
		            <table id="jqGrid_grpaccess" class="table table-striped"></table>
		            <div id="jqGridPager_grpaccess"></div>
		        </div>
		    </div>

		</div>
	
	</div>
	
	<!-------------------------------- table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%;' id='formdata'>

				<div class="well form-group" grpmaintenance style="margin-left: 0px">
				  <label class="control-label" for="groupid">Group ID</label> 
				  	<input id="groupid" name="groupid" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>


				  <label class="control-label" for="description">Description</label>  
				  	<input id="description" name="description" type="text" class="form-control input-sm text-uppercase">
                </div>

                <div class="well form-inline" grpaccess>
				  <label class="control-label" for="canrun">Can run</label>  
				 	<select class="form-control" id='canrun' name='canrun'>
				  		<option value='Yes'>Yes</option>
				  		<option value='No'>No</option>
				  	</select>


				  <label class="control-label" for="yesall">Yes All</label>  
				  	<select class="form-control" id='yesall' name='yesall'>
				  		<option value='Yes'>Yes</option>
				  		<option value='No'>No</option>
				  	</select>
                </div>
                
			</form>
		</div>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="group_maintenance.js"></script>
	<script src="../../../../assets/js/utility.js"></script>

<script>
		
</script>
</body>
</html>