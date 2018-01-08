<?php 
	include_once('../../../../header.php'); 
?>
<body>

	<input id="type2" name="type" type="hidden" value="<?php echo $_GET['type'];?>">
	 
	<!-------------------------------- Search + table ---------------------->

<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
			  <li ><a href="doctor.php" data-toggle="tab">Doctor</a></li>
			  <li ><a href="resource.php" >Resource</a></li>
			  <li><a href="ot.php" >Operation Theater</a></li>			  
			  <li><a href="test2.php" >test</a></li>
			 </ul>


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
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>		

			<input id="type2" name="type" type="hidden" value="<?php echo $_GET['type'];?>">						
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="resourcecode">Doctor Code</label>    
				  <div class="col-md-4">
                  	  <div class='input-group'>
						<input id="resourcecode" name="resourcecode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
					  </div>
				  </div>
                
                	<label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-4">
                      <input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
                
               <div class="form-group">  
				  <div class="col-md-4">
                  	  <div class='input-group'>
						<input id="Status" name="Status" type="hidden" class="form-control input-sm" data-validation="required">
					  </div>
				  </div>
                </div>
                
                <div class="form-group">
                      <label class="col-md-2 control-label" for="comment">Comment</label>  
                      <div class="col-md-6">
                      <input id="comment" name="comment" type="text" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>				
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
	<script src="doctor.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>