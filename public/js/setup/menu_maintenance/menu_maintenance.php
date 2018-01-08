<?php 
	include_once('../../../../header.php'); 
?>
<body>
	 
	<!-------------------------------- table ---------------------->
	<div class="well" style="margin: 10px;padding-top: 0px"><h4>Menu Navigation:</h4>
		<div id='btngroup' class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-primary" programid='main'>Main</button>
			</div>
		</div>
	</div>


	<div class='row'>
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
				<div class="well form-inline" style="margin-left: 0px">

				  <label class="control-label">Item Type
				  	<select class="form-control" id='programtype' name='programtype'>
				  		<option value='P'>Program</option>
				  		<option value='M'>Menu</option>
				  	</select></label>

				  <label class="control-label">Position
				  	<select class="form-control" id='whereat' name='whereat'>
				  		<option value='first'>First</option>
				  		<option value='last'>Last</option>
				  		<option value='after'>After</option>
				  	</select></label>

				  <label class="control-label">Program ID
				  	<select class="form-control" id='idAfter' name='idAfter'>
				  	</select></label>

                </div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="programid">Program ID</label>  
				  <div class="col-md-4">
				  	<input id="programid" name="programid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>


				  <label class="col-md-2 control-label" for="remarks">Remarks</label>  
				  <div class="col-md-4">
				  	<input id="remarks" name="remarks" type="text" class="form-control input-sm">
				  </div>
                </div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="programname">Description</label>  
				  <div class="col-md-10">
				  <input id="programname" name="programname" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                </div>

                <div class="form-group">
				  <label class="col-md-2 control-label" for="condition1">Condition 1</label>  
				  <div class="col-md-4">
				  <input id="condition1" name="condition1" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  </div>


				  <label class="col-md-2 control-label" for="condition2">Condition 2</label>  
				  <div class="col-md-4">
				  <input id="condition2" name="condition2" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  </div>
                </div>

				 <div class="form-group">
				  <label class="col-md-2 control-label" for="url">URL</label>  
				  <div class="col-md-10">
				  <input id="url" name="url" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                </div>
                
			</form>
		</div>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="menu_maintenance.js"></script>
	<script src="../../../../assets/js/utility.js"></script>

<script>
		
</script>
</body>
</html>