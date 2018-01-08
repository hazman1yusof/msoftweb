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
		<div class="panel panel-default">
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="prevnext btn-group pull-right">
					<a class='btn btn-default' name='prev'><i class='fa fa-chevron-left'></i></a>
					<a class='btn btn-primary' name='next' style='color:white'> Next <i class='fa fa-chevron-right'></i></a>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="tillcode">Till Code</label>  
				  <div class="col-md-4">
				  <input id="tillcode" name="tillcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
                </div>
				
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-8">
				  <input id="description" name="description" type="text" maxlength="40" class="form-control input-sm text-uppercase" data-validation="required">
				  </div>
                </div>
                
				<div class="form-group">
					<label class="col-md-2 control-label" for="dept">Department</label>  
					<div class="col-md-3">
					  <div class='input-group'>
						<input id="dept" name="dept" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
					
					<label class="col-md-2 control-label" for="effectdate">Effective Date</label>  
					<div class="col-md-3">
					<input id="effectdate" name="effectdate" type="date" class="form-control input-sm" data-validation="required">
					</div>
				  
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="defopenamt">Default Open Amount</label>  
				  <div class="col-md-3">
				  <input id="defopenamt" name="defopenamt" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
				  </div>
				  
				  <label class="col-md-2 control-label" for="tillstatus">Till Status</label>  
					<div class="col-md-3">
					<input id="tillstatus" name="tillstatus" type="text" class="form-control input-sm" value="C">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="lastrcnumber">Last No. Receipt</label>  
				  <div class="col-md-3">
				  <input id="lastrcnumber" name="lastrcnumber" type="text" class="form-control input-sm" value="0000000001">
				  </div>
				  
				  <label class="col-md-2 control-label" for="lastrefundno">Last No. Refund</label>  
					<div class="col-md-3">
					<input id="lastrefundno" name="lastrefundno" type="text" class="form-control input-sm" value="0000000001">
				  </div>
				  
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="lastcrnoteno">Last No. Credit note</label>  
				  <div class="col-md-3">
				  <input id="lastcrnoteno" name="lastcrnoteno" type="text" class="form-control input-sm" value="0000000001"/>
				  </div>
				  
				  <label class="col-md-2 control-label" for="lastinnumber">Last No. In</label>  
					<div class="col-md-3">
					<input id="lastinnumber" name="lastinnumber" type="text" class="form-control input-sm" value="0000000001">
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
	<script src="till.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>