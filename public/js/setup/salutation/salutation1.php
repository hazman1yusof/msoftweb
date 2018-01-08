<?php 
	include_once('../../../../header2.php'); 
?>
<body>
	 
	
	<form id="searchForm" style='width:99%'>
		<fieldset>
			<div id="searchInContainer">
					<div id="Scol">Search By : </div>
			</div>
		
			<div style="padding-left: 65px;margin-top: 25px;padding-right: 60%;">
				<input id="Stext" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
			</div>
		 </fieldset>  
	</form>
		
	<div class='col-md-12' style="padding-left:0">
		<table id="jqGrid" class="table table-striped"></table>
		<div id="jqGridPager"></div>
	</div>
        
       <div id="dialog" title="title">
        	<form id="checkForm" style="width:99%">
				<fieldset>
                    <div id="searchInContainer">
                    	Search By : <div id="Dcol" style="float:right; margin-right: 85px;"></div>
                   
                   		<input  style="float:left; margin-left: 73px;" id="Dtext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
                   </div>
				</fieldset>
			</form>
            
			<div class='col-xs-12' align="center">
            <br>
				<table id="gridDialog" class="table table-striped"></table>
				<div id="gridDialogPager"></div>
			</div>
		</div>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
			
			
				<div class="form-group">
                	<label class="col-md-3 control-label" for="RelationShipCode">RelationShip Code</label>  
                      <div class="col-md-4">
                      <input id="RelationShipCode" name="RelationShipCode" type="text" maxlength="8" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
                
                <div class="form-group">
                	<label class="col-md-3 control-label" for="Description">Description</label>  
                      <div class="col-md-8">
                      <input id="Description" name="Description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
                      </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-3 control-label" for="RecStatus">Record Status</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="RecStatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="RecStatus" value='D'>Deactive</label>
				  </div>
				</div>      
            </form>
			</div>
			
	<?php 
		include_once('../../../../footer2.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="salutationScript.js"></script>

<script>
		
</script>
</body>
</html>