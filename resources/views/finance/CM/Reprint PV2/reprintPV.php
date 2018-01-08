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
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
    </div>
	<!-------------------------------- End Search + table ------------------>
	<div id="dialogFormDP" title="Add Form" >
		<form class='form-horizontal' style='width:99%' id='formdataDP'>
				<input id="source" name="source">
				<input id="trantype" name="trantype">
		</form>
		
	</div>

	<div id="dialogFormFT" title="Add Form" >
		<form class='form-horizontal' style='width:99%' id='formdataFT'>
				<input id="source" name="source">
				<input id="trantype" name="trantype">
		</form>
	</div>
		
		

	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="reprintPV.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>