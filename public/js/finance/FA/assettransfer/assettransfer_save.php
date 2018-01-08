<?php 
	include_once('../../../../header.php'); 
?>

<body>

	 
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
		<button type="button" id='transferButn' class='btn btn-info pull-right' style='margin: 0.2%'>Transfer</button>
		<br><br><br>
		
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div
            
        </div>
    </div>
	<div>
	


	<!-------------------------------- End Search + table ------------------>
	<!------------------- Second Pop Up Form-------------------------------->
		
	<div id="msgBox" title="Message Box" style="display:none">
		<p>Are you sure you want to transfer asset</p>
		<ul>
			<li>itemcode: <span name='itemcode' ></span></li>
			<li>description: <span name='description' ></span></li>
		</ul>	
	</div>

	<!---------------------- Third form Transfer Form---------------------->

	<div id="dialogForm" title="Transfer Form">
			
			<form class='form-horizontal' style='width:89%' >
			<div class="prevnext btn-group pull-right"></div>
			<input id="source" name="source" type="hidden">
			<input id="trantype" name="trantype" type="hidden">
			<input id="auditno" name="auditno" type="hidden">


			<div class="form-group">
				<label class="col-md-2 control-label" for="assetno">Tagging No</label>
				<div class="col-md-2">
					<input id="assetno" name="assetno" type="text" class="form-control input-sm" frozeOnEdit>
				</div>
				<label class="col-md-3 control-label" for="description">Description</label>
				<div class="col-md-4">
					<input type="text" name="description" id="description" class="form-control input-sm" frozeOnEdit>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="currdeptcode">Current Department</label>
				<div class="col-md-3">
						<input type="text" name="currdeptcode" id="currdeptcode" class="form-control input-sm" frozeOnEdit>
				</div>
				<label class="col-md-2 control-label" for="currloccode">Current Location</label>
				<div class="col-md-3">
						<input type="text" name="currloccode" id="currloccode" class="form-control input-sm" frozeOnEdit>
				</div>
			</div>
			</form>

			<hr>
			<form class='form-horizontal' style='width:89%' id='formdata'>
				<div class="form-group">
					<label class="col-md-2 control-label" for="trandate">Transfer Date</label>
					<div class="col-md-2">
						<input type="date" name="trandate" id="trandate" class="form-control input-sm" data-validation="required">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="deptcode">New Department</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input type="text" name="deptcode" id="deptcode" class="form-control input-sm">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
					<label class="col-md-2 control-label" for="loccode">New Location</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input type="text" name="loccode" id="loccode" class="form-control input-sm">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
				</div>

				<!--<div class="form-group">
					<label class="col-md-2 control-label" for="description">Remarks</label>
					<div class="col-md-4">
						<input type="text" name="description" id="description" class="form-control input-sm" >
					</div>
				</div>-->

				
			</form>
		
	</div>
	<!--///////////////////End Form /////////////////////////-->


	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="assettransfer_save.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>