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

			
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="compcode">Company Code</label>  
				  <div class="col-md-4">
				  <input id="compcode" name="compcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
				</div>
				                  
				  <div class="form-group">
				  <label class="col-md-2 control-label" for="name">Name</label>  
				  <div class="col-md-8">
				  <input id="name" name="name" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="address1">Address</label>  
				  <div class="col-md-8">
				  <input id="address1" name="address1" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
				

				<!--------------------------------
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address2" name="address2" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address3" name="address3" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address4" name="address4" type="text" class="form-control input-sm">
				  </div>
				</div>

				 ------------------>
                				
 				<div class="form-group">
				  <label class="col-md-2 control-label" for="bmppath1">Bmppath</label>  
				  <div class="col-md-4">
				  <input id="bmppath1" name="bmppath1" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				 </div>
				
				<!--------------------------------
				<div class="form-group">
				<div class="col-md-offset-2 col-md-4">
				  <input id="bmppath2" name="bmppath2" type="text" class="form-control input-sm">
				  </div>
				</div>
						 ------------------>

				 <div class="form-group">
				  <label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
				  <div class="col-md-4">
				  <input ids="ipaddress" name="ipaddress" type="text" class="form-control input-sm">
				  </div>
				  
				  <label class="col-md-2 control-label" for="logo1">Logo</label>  
				  <div class="col-md-4">
				  <input id="logo1" name="logo1" type="text" class="form-control input-sm">
				  </div>
				</div>
				  
				 <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-10">
				    <label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>ACTIVE</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>DEACTIVE</label>
                </div>
				</div>




			</form>
		</div>

	<?php 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="compcode.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>