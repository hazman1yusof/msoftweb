<?php 
	include_once('../../../../header.php'); 
?>
<body>
<input id="Class2" name="Class" type="hidden" value="<?php echo $_GET['Class'];?>">
<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

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

 		<div class="panel panel-default">
		    <div class="panel-body">
		    	<div class='col-md-8'>
            		<table id="detail" class="table table-striped"></table>
            			<div id="jqGridPager2"></div>
        		</div>

        		<div class='col-md-4'>
            		<table id="itemExpiry" class="table table-striped"></table>
            			<div id="jqGridPager3"></div>
        		</div>
		    </div>
		</div>

    </div>

	<?php 
		include_once('../../../../footer.php');
	?>
	<script src="appointment2.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

</body>
</html>