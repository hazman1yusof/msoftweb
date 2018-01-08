<?php 
	include_once('../../../../header.php'); 
?>
<style>
	#gridAllo_c input[type='text'][rowid]{
		height: 30%;
		padding: 4px 12px 4px 12px;
	}
	#alloText{width:9%;}#alloText{width:60%;}#alloCol{width: 30%;}
	#alloCol, #alloText{
		display: inline-block;
		height: 70%;
		padding: 4px 12px 4px 12px;
	}
	#alloSearch{
		border-style: solid;
		border-width: 0px 1px 1px 1px;
		padding-top: 5px;
		padding-bottom: 5px;
		border-radius: 0px 0px 5px 5px;
		background-color: #f8f8f8;
		border-color: #e7e7e7;
	}
</style>
<body>
	 
	<!-------------------------------- table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass form-inline" style='padding-right: 0px'>
					<input name="Stext" type="search" placeholder="Search here ..." class="col-xs-2 form-control text-uppercase">
					<div class="col-xs-4 col-xs-offset-5">
						<label class='control-label' for='groupid'>Select Group ID</label>
					 	<select class='form-control' id='groupid' name='groupid'>
					 	<option>Select here ...</option>
					 	</select>
					 	<button type="button" class="btn btn-primary" id="msave">Save</button>
				 	</div>
				</div>
			 </fieldset>
		</form>

		<div class="panel panel-default">
		<div class="panel-body">
    	<div class='col-md-12' id='jqGrid_c' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
        </div>
        </div>
        </div>
        

          

    </div>
	<!-------------------------------- table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%;' id='formdata'>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="pageid">Page ID</label>  
				  <div class="col-md-4">
				  	<input id="pageid" name="pageid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
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


				  <label class="col-md-2 control-label" for="addate">Add Date</label>  
				  <div class="col-md-4">
				  <input id="addate" name="addate" type="text" class="form-control input-sm" rdonly>
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
	<script src="mpages.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>